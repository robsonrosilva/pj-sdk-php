<?php

namespace Inter\Sdk\sdkLibrary\banking\pix;

use Exception;
use Inter\Sdk\sdkLibrary\banking\models\IncludePixResponse;
use Inter\Sdk\sdkLibrary\banking\models\Pix;
use Inter\Sdk\sdkLibrary\banking\models\RetrievePixResponse;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Inter\Sdk\sdkLibrary\commons\utils\HttpUtils;
use Inter\Sdk\sdkLibrary\commons\utils\UrlUtils;
use Monolog\Logger;

class BankingPixClient
{
    /**
     * Includes a new PIX payment request in the banking system.
     *
     * @param Config $config The configuration object containing client information.
     * @param Pix $pix The Pix object containing details of the PIX payment to be included.
     * @return IncludePixResponse An object containing the response from the banking system after including the PIX payment request.
     * @throws SdkException If there is an error during the inclusion process.
     */
    public function includePix(Config $config, Pix $pix): IncludePixResponse
    {
        $log = new Logger('IncludePix');
        $log->info("IncludePix {$config->getClientId()} {$pix->getDescription()}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_PAYMENT_PIX);

        try {
            $jsonRequest = $pix->toJson();
            $jsonResponse = HttpUtils::callPost($config, $url, Constants::PIX_PAYMENT_WRITE_SCOPE, "Error including pix", $jsonRequest);
            return IncludePixResponse::fromJson(json_decode($jsonResponse, true));
        } catch (Exception $ioException) {
            $log->error(Constants::GENERIC_EXCEPTION_MESSAGE, ['exception' => $ioException]);
            throw new SdkException(
                $ioException->getMessage(),
                Error::builder()
                    ->title(Constants::CERTIFICATE_EXCEPTION_MESSAGE)
                    ->detail($ioException->getMessage())
                    ->build()
            );
        }
    }

    /**
     * Retrieves the details of a PIX payment request based on the given request code.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $requestCode The unique code of the PIX payment request to retrieve.
     * @return RetrievePixResponse An object containing the details of the requested PIX payment.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrievePix(Config $config, string $requestCode): RetrievePixResponse
    {
        $log = new Logger('RetrievePix');
        $log->info("RetrievePix {$config->getClientId()} {$requestCode}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_PAYMENT_PIX) . "/{$requestCode}";

        $jsonResponse = HttpUtils::callGet($config, $url, Constants::PIX_PAYMENT_READ_SCOPE, "Error retrieving pix");

        try {
            return RetrievePixResponse::fromJson(json_decode($jsonResponse, true));
        } catch (Exception $ioException) {
            $log->error(Constants::GENERIC_EXCEPTION_MESSAGE, ['exception' => $ioException]);
            throw new SdkException(
                $ioException->getMessage(),
                Error::builder()
                    ->title(Constants::CERTIFICATE_EXCEPTION_MESSAGE)
                    ->detail($ioException->getMessage())
                    ->build()
            );
        }
    }
}