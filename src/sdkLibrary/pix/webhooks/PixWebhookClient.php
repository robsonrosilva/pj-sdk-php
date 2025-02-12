<?php

namespace Inter\Sdk\sdkLibrary\pix\webhooks;

use Exception;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\models\IncludeWebhookRequest;
use Inter\Sdk\sdkLibrary\commons\models\Webhook;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Inter\Sdk\sdkLibrary\commons\utils\HttpUtils;
use Inter\Sdk\sdkLibrary\commons\utils\UrlUtils;
use Inter\Sdk\sdkLibrary\commons\utils\WebhookUtil;
use Inter\Sdk\sdkLibrary\pix\models\CallbackRetrieveFilter;
use Inter\Sdk\sdkLibrary\pix\models\PixCallbackPage;
use Inter\Sdk\sdkLibrary\pix\models\RetrieveCallbackResponse;
use Monolog\Logger;

class PixWebhookClient
{
    /**
     * Deletes a webhook identified by the provided key.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $key The unique key of the webhook to be deleted.
     * @throws SdkException If there is an error during the deletion process.
     */
    public function deleteWebhook(Config $config, string $key): void
    {
        $log = new Logger('DeleteWebhook');
        $log->info("DeleteWebhook pix {$config->getClientId()} {$key}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_WEBHOOK) . "/{$key}";

        HttpUtils::callDelete($config, $url, Constants::PIX_WEBHOOK_WRITE_SCOPE, "Error deleting webhook");
    }

    /**
     * Includes a webhook for the specified key and webhook URL.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $key The unique key for which the webhook is being included.
     * @param string $webhook_url The URL of the webhook to be included.
     * @throws SdkException If there is an error during the inclusion process.
     */
    public function includeWebhook(Config $config, string $key, string $webhook_url): void
    {
        $log = new Logger('IncludeWebhook');
        $log->info("IncludeWebhook pix {$config->getClientId()} {$key} {$webhook_url}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_WEBHOOK) . "/{$key}";
        $request = new IncludeWebhookRequest($webhook_url);

        WebhookUtil::includeWebhook($config, $url, $request, Constants::PIX_WEBHOOK_WRITE_SCOPE);
    }

    /**
     * Retrieves a paginated list of callback notifications based on specified date range and filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initial_date_hour The start date and time for the retrieval range (inclusive).
     * @param string $final_date_hour The end date and time for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int $page_size The number of items per page.
     * @param CallbackRetrieveFilter $filter An object containing filter criteria.
     * @return PixCallbackPage An object containing the requested page of callback notifications.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveCallbacksPage(Config $config, string $initial_date_hour, string $final_date_hour, int $page, int $page_size, CallbackRetrieveFilter $filter): PixCallbackPage
    {
        $log = new Logger('RetrieveCallbacks');
        $log->info("RetrieveCallbacks pix {$config->getClientId()} {$initial_date_hour} - {$final_date_hour}");

        return $this->getPage($config, $initial_date_hour, $final_date_hour, $page, $page_size, $filter);
    }

    /**
     * Retrieves all callback notifications within the specified date range and filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initial_date_hour The start date and time for the retrieval range (inclusive).
     * @param string $final_date_hour The end date and time for the retrieval range (inclusive).
     * @param CallbackRetrieveFilter $filter An object containing filter criteria.
     * @return RetrieveCallbackResponse[] A list of objects containing all retrieved callbacks.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveCallbacksInRange(Config $config, string $initial_date_hour, string $final_date_hour, CallbackRetrieveFilter $filter): array
    {
        $log = new Logger('RetrieveCallbacks');
        $log->info("RetrieveCallbacks pix {$config->getClientId()} {$initial_date_hour} - {$final_date_hour}");

        $page = 0;
        $callbacks = [];

        while (true) {
            $callback_page = $this->getPage($config, $initial_date_hour, $final_date_hour, $page, (int)null, $filter);
            $callbacks = array_merge($callbacks, $callback_page->getData());
            $page++;

            if ($page >= $callback_page->getTotalPages()) {
                break;
            }
        }

        return $callbacks;
    }

    /**
     * Retrieves a webhook identified by the provided key.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $key The unique key of the webhook to be retrieved.
     * @return Webhook An object containing the details of the retrieved webhook.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveWebhook(Config $config, string $key): Webhook
    {
        $log = new Logger('RetrieveWebhook');
        $log->info("RetrieveWebhook pix {$config->getClientId()} {$key}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_WEBHOOK) . "/{$key}";

        return WebhookUtil::retrieveWebhook($config, $url, Constants::PIX_WEBHOOK_READ_SCOPE);
    }

    /**
     * Retrieves a specific page of callback notifications based on the provided date range and filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initial_date_hour The start date and time for the retrieval range (inclusive).
     * @param string $final_date_hour The end date and time for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int $page_size The number of items per page.
     * @param CallbackRetrieveFilter $filter An object containing filter criteria.
     * @return PixCallbackPage An object containing the requested page of callback notifications.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function getPage(Config $config, string $initial_date_hour, string $final_date_hour, int $page, int $page_size, CallbackRetrieveFilter $filter): PixCallbackPage
    {
        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_WEBHOOK_CALLBACKS) .
            "?dataHoraInicio=" . urlencode($initial_date_hour) .
            "&dataHoraFim=" . urlencode($final_date_hour) .
            "&pagina={$page}";

        if ($page_size !== 0) {
            $url .= "&tamanhoPagina={$page_size}";
        }

        if ($filter !== null) {
            $url .= $this->addFilters($filter);
        }

        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_WEBHOOK_READ_SCOPE, "Error retrieving callbacks");

        try {
            return PixCallbackPage::fromJson(json_decode($json_response, true));
        } catch (Exception $io_exception) {
            $log = new Logger('GetPage');
            $log->error(Constants::GENERIC_EXCEPTION_MESSAGE, ['exception' => $io_exception]);
            throw new SdkException(
                $io_exception->getMessage(),
                new Error(Constants::CERTIFICATE_EXCEPTION_MESSAGE, $io_exception->getMessage())
            );
        }
    }

    /**
     * Adds filter parameters to the URL based on the provided filter criteria.
     *
     * @param CallbackRetrieveFilter $filter An object containing filter criteria.
     * @return string A string containing the appended filter parameters for the URL.
     */
    public function addFilters(CallbackRetrieveFilter $filter): string
    {
        if ($filter === null) {
            return "";
        }

        $string_filter = [];

        if ($filter->getTxid() !== null) {
            $string_filter[] = "&txid={$filter->getTxid()}";
        }

        return implode('', $string_filter);
    }
}