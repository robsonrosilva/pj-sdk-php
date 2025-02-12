<?php

namespace Inter\Sdk\sdkLibrary\banking\balance;

use Exception;
use Inter\Sdk\sdkLibrary\banking\models\Balance;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Inter\Sdk\sdkLibrary\commons\utils\HttpUtils;
use Inter\Sdk\sdkLibrary\commons\utils\UrlUtils;
use Monolog\Logger;

class BalanceClient
{

    /**
     * Retrieves the banking balance for a specified date.
     *
     * @param Config $config The configuration object containing necessary parameters such as client ID.
     * @param string $balanceDate The date for which the balance is requested, formatted as a string.
     * @return Balance A Balance object containing the balance details.
     * @throws SdkException If an error occurs during the retrieval of the balance or if an error
     *                      occurs during the JSON parsing.
     */
    public function retrieveBalance(Config $config, string $balanceDate): Balance
    {
        $log = new Logger('BalanceRetrieval');
        $log->info("BalanceClient banking... config.clientId = {$config->getClientId()}, balanceDate = {$balanceDate}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_BALANCE);
        if ($balanceDate !== "") {
            $url .= "?dataSaldo=" . urlencode($balanceDate);
        }

        $json = HttpUtils::callGet($config, $url, Constants::READ_BALANCE_SCOPE, "Error retrieving balance");
        try {
            return Balance::fromJson(json_decode($json, Balance::class));
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
