<?php

namespace Inter\Sdk\sdkLibrary\billing\webhooks;

use Exception;
use Inter\Sdk\sdkLibrary\billing\models\BillingCallbackPage;
use Inter\Sdk\sdkLibrary\billing\models\BillingRetrieveCallbackResponse;
use Inter\Sdk\sdkLibrary\billing\models\BillingRetrieveCallbacksFilter;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\models\IncludeWebhookRequest;
use Inter\Sdk\sdkLibrary\commons\models\Webhook;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Inter\Sdk\sdkLibrary\commons\utils\HttpUtils;
use Inter\Sdk\sdkLibrary\commons\utils\UrlUtils;
use Inter\Sdk\sdkLibrary\commons\utils\WebhookUtil;
use Monolog\Logger;

class BillingWebhookClient
{
    /**
     * Deletes the billing webhook associated with the specified configuration.
     *
     * @param Config $config The configuration object containing client information.
     * @throws SdkException If there is an error during the deletion process.
     */
    public function deleteWebhook(Config $config): void
    {
        $log = new Logger('DeleteWebhook');
        $log->info("DeleteWebhook billing {$config->getClientId()}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BILLING_WEBHOOK);

        try {
            HttpUtils::callDelete($config, $url, Constants::BILLET_BILLING_WRITE_SCOPE, "Error deleting webhook");
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
     * Includes a new webhook URL for billing notifications.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $webhookUrl The URL to be included as a webhook for billing notifications.
     * @throws SdkException If there is an error during the inclusion process.
     */
    public function includeWebhook(Config $config, string $webhookUrl): void
    {
        $log = new Logger('IncludeWebhook');
        $log->info("IncludeWebhook billing {$config->getClientId()} {$webhookUrl}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BILLING_WEBHOOK);
        $request = new IncludeWebhookRequest($webhookUrl);

        try {
            WebhookUtil::includeWebhook($config, $url, $request, Constants::BILLET_BILLING_WRITE_SCOPE);
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
     * Retrieves a page of callback responses based on the specified date range and optional filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDateHour The start date and hour for the retrieval range (inclusive).
     * @param string $finalDateHour The end date and hour for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param BillingRetrieveCallbacksFilter|null $filter Optional filters to be applied to the callback retrieval.
     * @return BillingCallbackPage An object containing the requested page of callback responses.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveCallbackPage(Config $config, string $initialDateHour, string $finalDateHour, int $page, ?int $pageSize, ?BillingRetrieveCallbacksFilter $filter): BillingCallbackPage
    {
        $log = new Logger('RetrieveCallback');
        $log->info("RetrieveCallback {$config->getClientId()} {$initialDateHour}-{$finalDateHour}");

        return $this->getPage($config, $initialDateHour, $finalDateHour, $page, $pageSize, $filter);
    }

    /**
     * Retrieves all callback responses within the specified date range, applying the given filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDateHour The start date and hour for the retrieval range (inclusive).
     * @param string $finalDateHour The end date and hour for the retrieval range (inclusive).
     * @param BillingRetrieveCallbacksFilter|null $filter Optional filters to be applied to the callback retrieval.
     * @param int $pageSize The number of items per page.
     * @return BillingRetrieveCallbackResponse[] A list containing all callback responses within the specified date range.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveCallbacksInRange(Config $config, string $initialDateHour, string $finalDateHour, ?BillingRetrieveCallbacksFilter $filter, int $pageSize): array
    {
        $log = new Logger('RetrieveCallback');
        $log->info("RetrieveCallback {$config->getClientId()} {$initialDateHour}-{$finalDateHour}");

        $page = 0;
        $callbacks = [];

        while (true) {
            $callbackPage = $this->getPage($config, $initialDateHour, $finalDateHour, $page, $pageSize, $filter);
            $callbacks = array_merge($callbacks, $callbackPage->getCallbacks());
            $page++;

            if ($page >= $callbackPage->getTotalPages()) {
                break;
            }
        }

        return $callbacks;
    }

    /**
     * Retrieves the webhook configuration associated with the specified client configuration.
     *
     * @param Config $config The configuration object containing client information.
     * @return Webhook An object containing the current webhook settings.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveWebhook(Config $config): Webhook
    {
        $log = new Logger('RetrieveWebhook');
        $log->info("RetrieveWebhook billing {$config->getClientId()}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BILLING_WEBHOOK);

        try {
            return WebhookUtil::retrieveWebhook($config, $url, Constants::BILLET_BILLING_READ_SCOPE);
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
     * Retrieves a specific page of callbacks from the webhook.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDateHour The start date and hour for the retrieval range (inclusive).
     * @param string $finalDateHour The end date and hour for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param BillingRetrieveCallbacksFilter|null $filter Optional filters to be applied to the callback retrieval.
     * @return BillingCallbackPage An object containing the requested page of callback responses.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function getPage(Config $config, string $initialDateHour, string $finalDateHour, int $page, ?int $pageSize, ?BillingRetrieveCallbacksFilter $filter): BillingCallbackPage
    {
        $url = UrlUtils::buildUrl($config, Constants::URL_BILLING_WEBHOOK_CALLBACKS) .
            "?dataHoraInicio=" . urlencode($initialDateHour) .
            "&dataHoraFim=" . urlencode($finalDateHour) .
            "&pagina={$page}";

        if ($pageSize !== null) {
            $url .= "&itensPorPagina={$pageSize}";
        }

        $url .= $this->addFilters($filter);

        $jsonResponse = HttpUtils::callGet($config, $url, Constants::BILLET_BILLING_READ_SCOPE, "Error retrieving callbacks");

        try {
            return BillingCallbackPage::fromJson(json_decode($jsonResponse, true));
        } catch (Exception $ioException) {
            $log = new Logger('GetPage');
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
     * Constructs the query string for filters to be applied when retrieving callbacks.
     *
     * @param BillingRetrieveCallbacksFilter|null $filter The filter object containing filtering criteria.
     * @return string A query string that can be appended to the URL for filtering.
     */
    public function addFilters(?BillingRetrieveCallbacksFilter $filter): string
    {
        if ($filter === null) {
            return "";
        }

        $stringFilter = [];

        if ($filter->getRequestCode() !== null) {
            $stringFilter[] = "&codigoSolicitacao={$filter->getRequestCode()}";
        }

        return implode('', $stringFilter);
    }
}