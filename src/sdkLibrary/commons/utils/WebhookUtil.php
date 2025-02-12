<?php

namespace Inter\Sdk\sdkLibrary\commons\utils;

use Exception;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\models\IncludeWebhookRequest;
use Inter\Sdk\sdkLibrary\commons\models\Webhook;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Monolog\Logger;

class WebhookUtil
{
    /**
     * Includes a new webhook configuration.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $url The URL to include the webhook.
     * @param IncludeWebhookRequest $request The request object containing webhook details.
     * @param string $scope The scope of the webhook.
     * @throws SdkException If there is an error during inclusion.
     */
    public static function includeWebhook(Config $config, string $url, IncludeWebhookRequest $request, string $scope): void
    {
        try {
            $jsonData = $request->toJson();
            HttpUtils::callPut($config, $url, $scope, "Error including webhook", $jsonData);
        } catch (Exception $ioException) {
            $log = new Logger('IncludeWebhook');
            $log->error("An error occurred: %s", [$ioException->getMessage()]);
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
     * Retrieves a webhook configuration.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $url The URL to retrieve the webhook.
     * @param string $scope The scope of the webhook.
     * @return Webhook Webhook The retrieved webhook object.
     * Webhook The retrieved webhook object.
     * @throws SdkException If there is an error during the retrieval.
     */
    public static function retrieveWebhook(Config $config, string $url, string $scope): Webhook
    {
        $jsonData = HttpUtils::callGet($config, $url, $scope, "Error retrieving webhook");
        try {
            return Webhook::fromJson(json_decode($jsonData, true));
        } catch (Exception $ioException) {
            $log = new Logger('RetrieveWebhook');
            $log->error("An error occurred: %s", [$ioException->getMessage()]);
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