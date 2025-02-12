<?php

namespace Inter\Sdk\sdkLibrary\billing;

use Inter\Sdk\sdkLibrary\billing\billing\BillingClient;
use Inter\Sdk\sdkLibrary\billing\models\BillingCallbackPage;
use Inter\Sdk\sdkLibrary\billing\models\BillingIssueRequest;
use Inter\Sdk\sdkLibrary\billing\models\BillingIssueResponse;
use Inter\Sdk\sdkLibrary\billing\models\BillingPage;
use Inter\Sdk\sdkLibrary\billing\models\BillingRetrievalFilter;
use Inter\Sdk\sdkLibrary\billing\models\BillingRetrieveCallbackResponse;
use Inter\Sdk\sdkLibrary\billing\models\BillingRetrieveCallbacksFilter;
use Inter\Sdk\sdkLibrary\billing\models\RetrievedBilling;
use Inter\Sdk\sdkLibrary\billing\models\Sorting;
use Inter\Sdk\sdkLibrary\billing\models\SummaryItem;
use Inter\Sdk\sdkLibrary\billing\webhooks\BillingWebhookClient;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Webhook;

class BillingSdk
{
    private Config $config;
    private ?BillingClient $billing_client = null;
    private ?BillingWebhookClient $billing_webhook_client = null;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Cancels a billing request specified by the request code.
     *
     * @param string $request_code The unique code identifying the billing request to be canceled.
     * @param string $cancellation_reason Reason for canceling the billing request.
     * @throws SdkException
     */
    public function cancelBilling(string $request_code, string $cancellation_reason): void
    {
        if ($this->billing_client === null) {
            $this->billing_client = new BillingClient();
        }

        $this->billing_client->cancelBilling($this->config, $request_code, $cancellation_reason);
    }

    /**
     * Issues a billing request based on the provided billing issue details.
     *
     * @param BillingIssueRequest $billing_issue_request The request object containing details for the billing issue.
     *
     * @return BillingIssueResponse A response object containing the outcome of the billing issue process.
     *
     * @throws SdkException If an error occurs during the billing issue process.
     */
    public function issueBilling(BillingIssueRequest $billing_issue_request): BillingIssueResponse
    {
        if ($this->billing_client === null) {
            $this->billing_client = new BillingClient();
        }

        return $this->billing_client->issueBilling($this->config, $billing_issue_request);
    }

    /**
     * Retrieves the billing information based on the specified request code.
     *
     * @param string $request_code The unique code identifying the billing request to retrieve.
     * @return RetrievedBilling An object containing the details of the retrieved billing information.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveBilling(string $request_code): RetrievedBilling
    {
        if ($this->billing_client === null) {
            $this->billing_client = new BillingClient();
        }

        return $this->billing_client->retrieveBilling($this->config, $request_code);
    }

    /**
     * Retrieves a collection of billing information for a specified period, applying optional filters and sorting.
     *
     * @param string $initial_date The starting date for the billing retrieval. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the billing retrieval. Format: YYYY-MM-DD.
     * @param BillingRetrievalFilter|null $filter Optional filter criteria to refine the billing retrieval.
     * @param Sorting|null $sort Optional sorting parameters for the retrieved collection.
     * @return RetrievedBilling[] A list of retrieved billing information objects.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveBillingCollection(string $initial_date, string $final_date, ?BillingRetrievalFilter $filter, ?Sorting $sort): array
    {
        if ($this->billing_client === null) {
            $this->billing_client = new BillingClient();
        }

        return $this->billing_client->retrieveBillingInRange($this->config, $initial_date, $final_date, $filter, $sort);
    }

    /**
     * Retrieves a paginated collection of billing information for a specified period, applying optional filters and sorting.
     *
     * @param string $initial_date The starting date for the billing retrieval. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the billing retrieval. Format: YYYY-MM-DD.
     * @param int $page The page number for pagination.
     * @param int|null $page_size The number of items per page. If null, default size will be used.
     * @param BillingRetrievalFilter|null $filter Optional filter criteria to refine the billing retrieval.
     * @param Sorting|null $sort Optional sorting parameters for the retrieved collection.
     * @return BillingPage A BillingPage object containing the retrieved billing information.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveBillingCollectionPage(string $initial_date, string $final_date, int $page, ?int $page_size, ?BillingRetrievalFilter $filter, ?Sorting $sort): BillingPage
    {
        if ($this->billing_client === null) {
            $this->billing_client = new BillingClient();
        }

        return $this->billing_client->retrieveBillingPage($this->config, $initial_date, $final_date, $page, $page_size, $filter, $sort);
    }

    /**
     * Retrieves the billing PDF document based on the specified request code and saves it to a file.
     *
     * @param string $request_code The unique code identifying the billing request for which the PDF should be retrieved.
     * @param string $file The path to the file where the PDF will be saved.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveBillingPdf(string $request_code, string $file): void
    {
        if ($this->billing_client === null) {
            $this->billing_client = new BillingClient();
        }

        $this->billing_client->retrieveBillingInPdf($this->config, $request_code, $file);
    }

    /**
     * Retrieves a summary of billing information for a specified period, applying optional filters.
     *
     * @param string $initial_date The starting date for the billing summary retrieval. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the billing summary retrieval. Format: YYYY-MM-DD.
     * @param BillingRetrievalFilter|null $filter Optional filter criteria to refine the billing summary retrieval.
     * @return SummaryItem[] A Summary object containing the billing information summary.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveBillingSummary(string $initial_date, string $final_date, ?BillingRetrievalFilter $filter): array
    {
        if ($this->billing_client === null) {
            $this->billing_client = new BillingClient();
        }

        return $this->billing_client->retrieveBillingSummary($this->config, $initial_date, $final_date, $filter);
    }

    /**
     * Retrieves a list of callback responses for a specified period, applying optional filters.
     *
     * @param string $initial_date_hour The starting date and hour for the callback retrieval. Format: YYYY-MM-DDTHH:mm.
     * @param string $final_date_hour The ending date and hour for the callback retrieval. Format: YYYY-MM-DDTHH:mm.
     * @param BillingRetrieveCallbacksFilter|null $filter Optional filter criteria to refine the callback retrieval.
     * @param int $page_size The number of items per page.
     * @return BillingRetrieveCallbackResponse[] A list of RetrieveCallbackResponse objects containing the retrieved callback information.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveCallbacks(string $initial_date_hour, string $final_date_hour, ?BillingRetrieveCallbacksFilter $filter, int $page_size): array
    {
        if ($this->billing_webhook_client === null) {
            $this->billing_webhook_client = new BillingWebhookClient();
        }
        return $this->billing_webhook_client->retrieveCallbacksInRange($this->config, $initial_date_hour, $final_date_hour, $filter, $page_size);
    }
    /**
     * Retrieves a paginated list of callbacks for a specified period, applying optional filters.
     *
     * @param string $initial_date_hour The starting date and hour for the callback retrieval. Format: YYYY-MM-DDTHH:mm.
     * @param string $final_date_hour The ending date and hour for the callback retrieval. Format: YYYY-MM-DDTHH:mm.
     * @param int $page The page number for pagination.
     * @param int $page_size The number of items per page.
     * @param BillingRetrieveCallbacksFilter|null $filter Optional filter criteria to refine the callback retrieval.
     * @return BillingCallbackPage A CallbackPage object containing the paginated list of retrieved callbacks.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveCallbacksPage(string $initial_date_hour, string $final_date_hour, int $page, int $page_size, ?BillingRetrieveCallbacksFilter $filter): BillingCallbackPage
    {
        if ($this->billing_webhook_client === null) {
            $this->billing_webhook_client = new BillingWebhookClient();
        }
        return $this->billing_webhook_client->retrieveCallbackPage($this->config, $initial_date_hour, $final_date_hour, $page, $page_size, $filter);
    }
    /**
     * Includes a webhook URL for receiving notifications.
     *
     * @param string $url The URL of the webhook to be included.
     * @throws SdkException If an error occurs during the inclusion process.
     */
    public function includeWebhook(string $url): void
    {
        if ($this->billing_webhook_client === null) {
            $this->billing_webhook_client = new BillingWebhookClient();
        }
        $this->billing_webhook_client->includeWebhook($this->config, $url);
    }
    /**
     * Retrieves the currently configured webhook information.
     *
     * @return Webhook A Webhook object containing the details of the configured webhook.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveWebhook(): Webhook
    {
        if ($this->billing_webhook_client === null) {
            $this->billing_webhook_client = new BillingWebhookClient();
        }
        return $this->billing_webhook_client->retrieveWebhook($this->config);
    }
    /**
     * Deletes the currently configured webhook.
     *
     * @throws SdkException If an error occurs during the deletion process.
     */
    public function deleteWebhook(): void
    {
        if ($this->billing_webhook_client === null) {
            $this->billing_webhook_client = new BillingWebhookClient();
        }
        $this->billing_webhook_client->deleteWebhook($this->config);
    }
}