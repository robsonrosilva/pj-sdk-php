<?php

namespace Inter\Sdk\sdkLibrary\banking\webhooks;

use Exception;
use Inter\Sdk\sdkLibrary\banking\models\CallbackPage;
use Inter\Sdk\sdkLibrary\banking\models\CallbackRetrieveFilter;
use Inter\Sdk\sdkLibrary\banking\models\RetrieveCallbackResponse;
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

class BankingWebhookClient
{
    /**
     * Deletes a specified webhook based on its type.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $webhookType The type of the webhook to be deleted.
     * @throws SdkException If there is an error during the deletion process.
     */
    public function deleteWebhook(Config $config, string $webhookType): void
    {
        $log = new Logger('DeleteWebhook');
        $log->info("DeleteWebhook banking {$config->getClientId()} {$webhookType}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_WEBHOOK) . "/{$webhookType}";

        try {
            HttpUtils::callDelete($config, $url, Constants::WEBHOOK_BANKING_WRITE_SCOPE, "Error deleting webhook");
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
     * Includes a new webhook configuration for a specified type and URL.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $webhookType The type of the webhook to be included.
     * @param string $webhookUrl The URL where the webhook will send notifications.
     * @throws SdkException If there is an error during the inclusion process.
     */
    public function includeWebhook(Config $config, string $webhookType, string $webhookUrl): void
    {
        $log = new Logger('IncludeWebhookBanking');
        $log->info("IncludeWebhookBanking {$config->getClientId()} {$webhookType} {$webhookUrl}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_WEBHOOK) . "/{$webhookType}";
        $request = new IncludeWebhookRequest($webhookUrl);

        try {
            WebhookUtil::includeWebhook($config, $url, $request, Constants::WEBHOOK_BANKING_WRITE_SCOPE);
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
     * Retrieves a page of callback responses for a specified webhook type within a given date range.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $webhookType The type of the webhook to retrieve callbacks for.
     * @param string $initialDateHour The start date and hour for the retrieval range (inclusive).
     * @param string $finalDateHour The end date and hour for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param CallbackRetrieveFilter|null $filter Optional filters to apply to the callback retrieval.
     * @return CallbackPage An object containing the requested page of callback responses.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveCallbacksPage(Config $config, string $webhookType, string $initialDateHour, string $finalDateHour, int $page, ?int $pageSize, ?CallbackRetrieveFilter $filter): CallbackPage
    {
        $log = new Logger('RetrieveCallbacks');
        $log->info("RetrieveCallbacks {$config->getClientId()} {$initialDateHour}-{$finalDateHour}");

        return $this->getPage($config, $webhookType, $initialDateHour, $finalDateHour, $page, $pageSize, $filter);
    }

    /**
     * Retrieves all callback responses for a specified webhook type within a given date range.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $webhookType The type of the webhook to retrieve callbacks for.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param CallbackRetrieveFilter|null $filter Optional filters to apply to the callback retrieval.
     * @return RetrieveCallbackResponse[] A list containing all callback responses within the specified date range.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveCallbacksInRange(Config $config, string $webhookType, string $initialDate, string $finalDate, ?CallbackRetrieveFilter $filter): array
    {
        $log = new Logger('RetrieveCallbacks');
        $log->info("RetrieveCallbacks {$config->getClientId()} {$initialDate}-{$finalDate}");

        $page = 0;
        $callbacks = [];

        while (true) {
            $callbackPage = $this->getPage($config, $webhookType, $initialDate, $finalDate, $page, null, $filter);

            if ($callbackPage->getData() !== null) {
                $callbacks = array_merge($callbacks, $callbackPage->getData());
            }

            $page++;
            if ($page >= $callbackPage->getTotalPages()) {
                break;
            }
        }

        return $callbacks;
    }

    /**
     * Retrieves the configuration for a specified webhook type.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $webhookType The type of the webhook to be retrieved.
     * @return Webhook An object containing the configuration details of the requested webhook.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveWebhook(Config $config, string $webhookType): Webhook
    {
        $log = new Logger('RetrieveWebhook');
        $log->info("RetrieveWebhook banking {$config->getClientId()} {$webhookType}");
        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_WEBHOOK) . "/{$webhookType}";
        try {
            return WebhookUtil::retrieveWebhook($config, $url, Constants::WEBHOOK_BANKING_READ_SCOPE);
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
     * Retrieves a specific page of callback responses for a specified webhook type.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $webhookType The type of the webhook to retrieve callbacks for.
     * @param string $initialDateHour The start date and hour for the retrieval range (inclusive).
     * @param string $finalDateHour The end date and hour for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param CallbackRetrieveFilter|null $filter Optional filters to apply to the callback retrieval.
     * @return CallbackPage An object containing the requested page of callback responses.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function getPage(Config $config, string $webhookType, string $initialDateHour, string $finalDateHour, int $page, ?int $pageSize, ?CallbackRetrieveFilter $filter): CallbackPage
    {
        $log = new Logger('GetPage');
        $url1 = UrlUtils::buildUrl($config, Constants::URL_BANKING_WEBHOOK) . "/{$webhookType}/callbacks";
        $url = "{$url1}?dataHoraInicio=" . urlencode($initialDateHour) . "&dataHoraFim=" . urlencode($finalDateHour) . "&pagina={$page}";
        if ($pageSize !== null) {
            $url .= "&tamanhoPagina={$pageSize}";
        }
        $url .= $this->addFilters($filter);
        $jsonResponse = HttpUtils::callGet($config, $url, Constants::WEBHOOK_BANKING_READ_SCOPE, "Error retrieving callbacks");
        try {
            return CallbackPage::fromJson(json_decode($jsonResponse, true));
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
     * Constructs the query string for filters to be applied when retrieving callbacks.
     *
     * @param CallbackRetrieveFilter|null $filter The filter object containing filtering criteria.
     * @return string A query string that can be appended to the URL for filtering.
     */
    public function addFilters(?CallbackRetrieveFilter $filter): string
    {
        if ($filter === null) {
            return "";
        }
        $stringFilter = [];
        if ($filter->getTransactionCode() !== null) {
            $stringFilter[] = "&codigoTransacao={$filter->getTransactionCode()}";
        }
        if ($filter->getEndToEndId() !== null) {
            $stringFilter[] = "&endToEnd={$filter->getEndToEndId()}";
        }
        return implode('', $stringFilter);
    }
}