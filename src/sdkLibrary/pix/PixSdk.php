<?php

namespace Inter\Sdk\sdkLibrary\pix;

use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Webhook;
use Inter\Sdk\sdkLibrary\pix\duebilling\DueBillingClient;
use Inter\Sdk\sdkLibrary\pix\duebillingbatch\DueBillingBatchClient;
use Inter\Sdk\sdkLibrary\pix\enums\ImmediateBillingType;
use Inter\Sdk\sdkLibrary\pix\immediatebillings\ImmediateBillingClient;
use Inter\Sdk\sdkLibrary\pix\locations\LocationClient;
use Inter\Sdk\sdkLibrary\pix\models\BillingPage;
use Inter\Sdk\sdkLibrary\pix\models\CallbackRetrieveFilter;
use Inter\Sdk\sdkLibrary\pix\models\DetailedDevolution;
use Inter\Sdk\sdkLibrary\pix\models\DetailedDuePixBilling;
use Inter\Sdk\sdkLibrary\pix\models\DetailedImmediatePixBilling;
use Inter\Sdk\sdkLibrary\pix\models\DevolutionRequestBody;
use Inter\Sdk\sdkLibrary\pix\models\DueBilling;
use Inter\Sdk\sdkLibrary\pix\models\DueBillingBatch;
use Inter\Sdk\sdkLibrary\pix\models\DueBillingBatchPage;
use Inter\Sdk\sdkLibrary\pix\models\DueBillingBatchSummary;
use Inter\Sdk\sdkLibrary\pix\models\DueBillingPage;
use Inter\Sdk\sdkLibrary\pix\models\GeneratedDueBilling;
use Inter\Sdk\sdkLibrary\pix\models\GeneratedImmediateBilling;
use Inter\Sdk\sdkLibrary\pix\models\IncludeDueBillingBatchRequest;
use Inter\Sdk\sdkLibrary\pix\models\Location;
use Inter\Sdk\sdkLibrary\pix\models\LocationPage;
use Inter\Sdk\sdkLibrary\pix\models\Pix;
use Inter\Sdk\sdkLibrary\pix\models\PixBilling;
use Inter\Sdk\sdkLibrary\pix\models\PixCallbackPage;
use Inter\Sdk\sdkLibrary\pix\models\PixPage;
use Inter\Sdk\sdkLibrary\pix\models\RetrieveCallbackResponse;
use Inter\Sdk\sdkLibrary\pix\models\RetrievedPixFilter;
use Inter\Sdk\sdkLibrary\pix\models\RetrieveDueBillingFilter;
use Inter\Sdk\sdkLibrary\pix\models\RetrieveImmediateBillingsFilter;
use Inter\Sdk\sdkLibrary\pix\models\RetrieveLocationFilter;
use Inter\Sdk\sdkLibrary\pix\pix\PixClient;
use Inter\Sdk\sdkLibrary\pix\webhooks\PixWebhookClient;

class PixSdk
{
    private Config $config;
    private ?DueBillingClient $due_billing_client = null;
    private ?DueBillingBatchClient $due_billing_batch_client = null;
    private ?ImmediateBillingClient $immediate_billing_client = null;
    private ?LocationClient $location_client = null;
    private ?PixClient $pix_client = null;
    private ?PixWebhookClient $pix_webhook_sdk = null;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Includes a due billing entry for a PIX transaction.
     *
     * @param string $txid The transaction ID associated with the due billing.
     * @param DueBilling $billing The DueBilling object containing the billing details to be included.
     * @return GeneratedDueBilling A GeneratedDueBilling object containing the details of the included due billing.
     * @throws SdkException If an error occurs during the inclusion process.
     */
    public function includeDuePixBilling(string $txid, DueBilling $billing): GeneratedDueBilling
    {
        if ($this->due_billing_client === null) {
            $this->due_billing_client = new DueBillingClient();
        }

        return $this->due_billing_client->includeDueBilling($this->config, $txid, $billing);
    }

    /**
     * Retrieves the detailed due billing information for a specific PIX transaction.
     *
     * @param string $txid The transaction ID associated with the due billing to be retrieved.
     * @return DetailedDuePixBilling A DetailedDuePixBilling object containing the details of the retrieved due billing.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveDuePixBilling(string $txid): DetailedDuePixBilling
    {
        if ($this->due_billing_client === null) {
            $this->due_billing_client = new DueBillingClient();
        }

        return $this->due_billing_client->retrieveDueBilling($this->config, $txid);
    }

    /**
     * Retrieves a list of detailed due billing entries for a specified period, applying optional filters.
     *
     * @param string $initial_date The starting date for the billing collection retrieval. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the billing collection retrieval. Format: YYYY-MM-DD.
     * @param RetrieveDueBillingFilter|null $filter Optional filter criteria to refine the billing collection retrieval.
     * @return DetailedDuePixBilling[] A list of DetailedDuePixBilling objects containing the retrieved billing information.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveDueBillingCollectionInRange(string $initial_date, string $final_date, ?RetrieveDueBillingFilter $filter): array
    {
        if ($this->due_billing_client === null) {
            $this->due_billing_client = new DueBillingClient();
        }

        return $this->due_billing_client->retrieveDueBillingsInRange($this->config, $initial_date, $final_date, $filter);
    }

    /**
     * Retrieves a paginated collection of due billing entries for a specified period, applying optional filters.
     *
     * @param string $initial_date The starting date for the billing collection retrieval. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the billing collection retrieval. Format: YYYY-MM-DD.
     * @param int $page The page number for pagination.
     * @param int $page_size The number of items per page. If null, a default size will be used.
     * @param RetrieveDueBillingFilter|null $filter Optional filter criteria to refine the billing collection retrieval.
     * @return DueBillingPage A DueBillingPage object containing the paginated list of retrieved due billing entries.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveDueBillingCollectionPage(string $initial_date, string $final_date, int $page, int $page_size, ?RetrieveDueBillingFilter $filter): DueBillingPage
    {
        if ($this->due_billing_client === null) {
            $this->due_billing_client = new DueBillingClient();
        }

        return $this->due_billing_client->retrieveDueBillingPage($this->config, $initial_date, $final_date, $page, $page_size, $filter);
    }

    /**
     * Reviews a due billing entry for a PIX transaction.
     *
     * @param string $txid The transaction ID associated with the due billing to be reviewed.
     * @param DueBilling $billing The DueBilling object containing the billing details to be reviewed.
     * @return GeneratedDueBilling A GeneratedDueBilling object containing the details of the reviewed due billing.
     * @throws SdkException If an error occurs during the review process.
     */
    public function reviewDuePixBilling(string $txid, DueBilling $billing): GeneratedDueBilling
    {
        if ($this->due_billing_client === null) {
            $this->due_billing_client = new DueBillingClient();
        }

        return $this->due_billing_client->reviewDueBilling($this->config, $txid, $billing);
    }

    /**
     * Includes a batch of due billing entries for a specific PIX transaction.
     *
     * @param string $txid The transaction ID associated with the due billing batch.
     * @param IncludeDueBillingBatchRequest $batch_request The IncludeDueBillingBatchRequest object containing the details of the billing batch to be included.
     * @throws SdkException If an error occurs during the inclusion process.
     */
    public function includeDueBillingBatch(string $txid, IncludeDueBillingBatchRequest $batch_request): void
    {
        if ($this->due_billing_batch_client === null) {
            $this->due_billing_batch_client = new DueBillingBatchClient();
        }

        $this->due_billing_batch_client->includeDueBillingBatch($this->config, $txid, $batch_request);
    }

    /**
     * Retrieves a due billing batch by its identifier.
     *
     * @param string $id The identifier of the billing batch to be retrieved.
     * @return DueBillingBatch A DueBillingBatch object containing the details of the retrieved billing batch.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveDueBillingBatch(string $id): DueBillingBatch
    {
        if ($this->due_billing_batch_client === null) {
            $this->due_billing_batch_client = new DueBillingBatchClient();
        }

        return $this->due_billing_batch_client->retrieveDueBillingBatch($this->config, $id);
    }

    /**
     * Retrieves a paginated collection of due billing batches for a specified period.
     *
     * @param string $initial_date The starting date for the billing batch collection retrieval. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the billing batch collection retrieval. Format: YYYY-MM-DD.
     * @param int $page The page number for pagination.
     * @param int $page_size The number of items per page. If null, a default size will be used.
     * @return DueBillingBatchPage A DueBillingBatchPage object containing the paginated list of retrieved due billing batches.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveDueBillingBatchCollectionPage(string $initial_date, string $final_date, int $page, int $page_size): DueBillingBatchPage
    {
        if ($this->due_billing_batch_client === null) {
            $this->due_billing_batch_client = new DueBillingBatchClient();
        }

        return $this->due_billing_batch_client->retrieveDueBillingBatchPage($this->config, $initial_date, $final_date, $page, $page_size);
    }

    /**
     * Retrieves a list of due billing batches for a specified period.
     *
     * @param string $initial_date The starting date for the billing batch collection retrieval. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the billing batch collection retrieval. Format: YYYY-MM-DD.
     * @return DueBillingBatch[] A list of DueBillingBatch objects containing the retrieved billing batches.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveDueBillingBatchCollectionInRange(string $initial_date, string $final_date): array
    {
        if ($this->due_billing_batch_client === null) {
            $this->due_billing_batch_client = new DueBillingBatchClient();
        }

        return $this->due_billing_batch_client->retrieveDueBillingBatchesInRange($this->config, $initial_date, $final_date);
    }

    /**
     * Retrieves the situation of a specific due billing batch by its identifier.
     *
     * @param string $id The identifier of the billing batch whose situation is to be retrieved.
     * @param string $situation The specific situation to filter the results.
     * @return DueBillingBatch A DueBillingBatch object containing the details of the retrieved billing batch situation.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveDueBillingBatchBySituation(string $id, string $situation): DueBillingBatch
    {
        if ($this->due_billing_batch_client === null) {
            $this->due_billing_batch_client = new DueBillingBatchClient();
        }

        return $this->due_billing_batch_client->retrieveDueBillingBatchBySituation($this->config, $id, $situation);
    }

    /**
     * Retrieves the summary of a specific due billing batch by its identifier.
     *
     * @param string $id The identifier of the billing batch whose summary is to be retrieved.
     * @return DueBillingBatchSummary A DueBillingBatchSummary object containing the summary details of the retrieved billing batch.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveDueBillingBatchSummary(string $id): DueBillingBatchSummary
    {
        if ($this->due_billing_batch_client === null) {
            $this->due_billing_batch_client = new DueBillingBatchClient();
        }

        return $this->due_billing_batch_client->retrieveDueBillingBatchSummary($this->config, $id);
    }

    /**
     * Reviews a due billing batch identified by its ID.
     *
     * @param string $id The identifier of the billing batch to be reviewed.
     * @param IncludeDueBillingBatchRequest $request The IncludeDueBillingBatchRequest object containing details for the review process.
     * @throws SdkException If an error occurs during the review process.
     */
    public function reviewDueBillingBatch(string $id, IncludeDueBillingBatchRequest $request): void
    {
        if ($this->due_billing_batch_client === null) {
            $this->due_billing_batch_client = new DueBillingBatchClient();
        }

        $this->due_billing_batch_client->reviewDueBillingBatch($this->config, $id, $request);
    }

    /**
     * Includes an immediate billing entry for a PIX transaction.
     *
     * @param PixBilling $billing The PixBilling object containing the details of the immediate billing to be included.
     * @return GeneratedImmediateBilling A GeneratedImmediateBilling object containing the details of the included immediate billing.
     * @throws SdkException|\DateMalformedStringException If an error occurs during the inclusion process.
     */
    public function includeImmediateBilling(PixBilling $billing): GeneratedImmediateBilling
    {
        if ($this->immediate_billing_client === null) {
            $this->immediate_billing_client = new ImmediateBillingClient();
        }

        return $this->immediate_billing_client->includeImmediateBilling($this->config, $billing);
    }

    /**
     * Retrieves the details of an immediate billing entry by its transaction ID.
     *
     * @param string $txid The transaction ID associated with the immediate billing to be retrieved.
     * @return DetailedImmediatePixBilling A DetailedImmediatePixBilling object containing the details of the retrieved immediate billing.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveImmediateBilling(string $txid): DetailedImmediatePixBilling
    {
        if ($this->immediate_billing_client === null) {
            $this->immediate_billing_client = new ImmediateBillingClient();
        }

        return $this->immediate_billing_client->retrieveImmediateBilling($this->config, $txid);
    }

    /**
     * Retrieves a list of detailed immediate billing entries for a specified period, optionally filtered.
     *
     * @param string $initial_date The starting date for the retrieval of immediate billings. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the retrieval of immediate billings. Format: YYYY-MM-DD.
     * @param RetrieveImmediateBillingsFilter|null $filter The filter criteria for retrieving the immediate billings.
     * @return DetailedImmediatePixBilling[] A list of DetailedImmediatePixBilling objects containing the details of the retrieved immediate billings.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveImmediateBillingList(string $initial_date, string $final_date, ?RetrieveImmediateBillingsFilter $filter): array
    {
        if ($this->immediate_billing_client === null) {
            $this->immediate_billing_client = new ImmediateBillingClient();
        }

        return $this->immediate_billing_client->retrieveImmediateBillingsInRange($this->config, $initial_date, $final_date, $filter);
    }

    /**
     * Retrieves a paginated list of immediate billing entries for a specified period, optionally filtered.
     *
     * @param string $initial_date The starting date for the retrieval of immediate billings. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the retrieval of immediate billings. Format: YYYY-MM-DD.
     * @param int $page The page number for pagination.
     * @param int $page_size The number of items per page. If null, a default size will be used.
     * @param RetrieveImmediateBillingsFilter|null $filter The filter criteria for retrieving the immediate billings.
     * @return BillingPage A BillingPage object containing the paginated list of retrieved immediate billings.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveImmediateBillingPage(string $initial_date, string $final_date, int $page, int $page_size, ?RetrieveImmediateBillingsFilter $filter): BillingPage
    {
        if ($this->immediate_billing_client === null) {
            $this->immediate_billing_client = new ImmediateBillingClient();
        }

        return $this->immediate_billing_client->retrieveImmediateBillingPage($this->config, $initial_date, $final_date, $page, $page_size, $filter);
    }

    /**
     * Reviews an immediate billing entry for a PIX transaction.
     *
     * @param PixBilling $billing The PixBilling object containing the details of the immediate billing to be reviewed.
     * @return GeneratedImmediateBilling A GeneratedImmediateBilling object containing the details of the reviewed immediate billing.
     * @throws SdkException If an error occurs during the review process.
     */
    public function reviewImmediateBilling(PixBilling $billing): GeneratedImmediateBilling
    {
        if ($this->immediate_billing_client === null) {
            $this->immediate_billing_client = new ImmediateBillingClient();
        }

        return $this->immediate_billing_client->reviewImmediateBilling($this->config, $billing);
    }

    /**
     * Includes a location associated with an immediate billing type.
     *
     * @param ImmediateBillingType $immediate_billing_type The ImmediateBillingType object containing the details of the location to be included.
     * @return Location A Location object containing the details of the included location.
     * @throws SdkException|\DateMalformedStringException If an error occurs during the inclusion process.
     */
    public function includeLocation(ImmediateBillingType $immediate_billing_type): Location
    {
        if ($this->location_client === null) {
            $this->location_client = new LocationClient();
        }

        return $this->location_client->includeLocation($this->config, $immediate_billing_type);
    }

    /**
     * Retrieves a location by its identifier.
     *
     * @param string $location_id The identifier of the location to be retrieved.
     * @return Location A Location object containing the details of the retrieved location.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveLocation(string $location_id): Location
    {
        if ($this->location_client === null) {
            $this->location_client = new LocationClient();
        }

        return $this->location_client->retrieveLocation($this->config, $location_id);
    }

    /**
     * Retrieves a list of locations for a specified period, optionally filtered.
     *
     * @param string $initial_date The starting date for the retrieval of locations. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the retrieval of locations. Format: YYYY-MM-DD.
     * @param RetrieveLocationFilter|null $filter The filter criteria for retrieving the locations.
     * @return Location[] A list of Location objects containing the details of the retrieved locations.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveLocationsList(string $initial_date, string $final_date, ?RetrieveLocationFilter $filter): array
    {
        if ($this->location_client === null) {
            $this->location_client = new LocationClient();
        }

        return $this->location_client->retrieveLocationInRange($this->config, $initial_date, $final_date, $filter);
    }

    /**
     * Retrieves a paginated list of locations for a specified period, optionally filtered.
     *
     * @param string $initial_date The starting date for the retrieval of locations. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the retrieval of locations. Format: YYYY-MM-DD.
     * @param int $page The page number for pagination.
     * @param int $page_size The number of items per page. If null, a default size will be used.
     * @param RetrieveLocationFilter|null $filter The filter criteria for retrieving the locations.
     * @return LocationPage A LocationPage object containing the paginated list of retrieved locations.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveLocationsPage(string $initial_date, string $final_date, int $page, int $page_size, ?RetrieveLocationFilter $filter): LocationPage
    {
        if ($this->location_client === null) {
            $this->location_client = new LocationClient();
        }

        return $this->location_client->retrieveLocationPage($this->config, $initial_date, $final_date, $page, $page_size, $filter);
    }

    /**
     * Unlinks a location by its identifier.
     *
     * @param string $id The identifier of the location to be unlinked.
     * @return Location A Location object containing the details of the unlinked location.
     * @throws SdkException If an error occurs during the unlinking process.
     */
    public function unlinkLocation(string $id): Location
    {
        if ($this->location_client === null) {
            $this->location_client = new LocationClient();
        }

        return $this->location_client->unlinkLocation($this->config, $id);
    }

    /**
     * Requests a devolution for a specific transaction.
     *
     * @param string $e2e_id The end-to-end identifier for the transaction.
     * @param string $id The identifier of the devolution request.
     * @param DevolutionRequestBody $devolution_request_body The body containing the details for the devolution request.
     * @return DetailedDevolution A DetailedDevolution object containing the details of the requested devolution.
     * @throws SdkException If an error occurs during the request process.
     */
    public function requestDevolution(string $e2e_id, string $id, DevolutionRequestBody $devolution_request_body): DetailedDevolution
    {
        if ($this->pix_client === null) {
            $this->pix_client = new PixClient();
        }

        return $this->pix_client->requestDevolution($this->config, $e2e_id, $id, $devolution_request_body);
    }

    /**
     * Retrieves the details of a specific devolution by its identifiers.
     *
     * @param string $e2e_id The end-to-end identifier for the transaction.
     * @param string $id The identifier of the devolution to be retrieved.
     * @return DetailedDevolution A DetailedDevolution object containing the details of the retrieved devolution.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveDevolution(string $e2e_id, string $id): DetailedDevolution
    {
        if ($this->pix_client === null) {
            $this->pix_client = new PixClient();
        }

        return $this->pix_client->retrieveDevolution($this->config, $e2e_id, $id);
    }

    /**
     * Retrieves a list of PIX transactions for a specified period, optionally filtered.
     *
     * @param string $initial_date The starting date for the retrieval of PIX transactions. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the retrieval of PIX transactions. Format: YYYY-MM-DD.
     * @param RetrievedPixFilter|null $filter The filter criteria for retrieving the PIX transactions.
     * @return Pix[] A list of Pix objects containing the details of the retrieved PIX transactions.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrievePixList(string $initial_date, string $final_date, ?RetrievedPixFilter $filter): array
    {
        if ($this->pix_client === null) {
            $this->pix_client = new PixClient();
        }

        return $this->pix_client->retrievePixListInRange($this->config, $initial_date, $final_date, $filter);
    }

    /**
     * Retrieves a paginated list of PIX transactions for a specified period, optionally filtered.
     *
     * @param string $initial_date The starting date for the retrieval of PIX transactions. Format: YYYY-MM-DD.
     * @param string $final_date The ending date for the retrieval of PIX transactions. Format: YYYY-MM-DD.
     * @param int $page The page number for pagination.
     * @param int $page_size The number of items per page. If null, a default size will be used.
     * @param RetrievedPixFilter|null $filter The filter criteria for retrieving the PIX transactions.
     * @return PixPage A PixPage object containing the paginated list of retrieved PIX transactions.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrievePixPage(string $initial_date, string $final_date, int $page, int $page_size, ?RetrievedPixFilter $filter): PixPage
    {
        if ($this->pix_client === null) {
            $this->pix_client = new PixClient();
        }

        return $this->pix_client->retrievePixPage($this->config, $initial_date, $final_date, $page, $page_size, $filter);
    }

    /**
     * Retrieves the details of a specific PIX transaction by its end-to-end identifier.
     *
     * @param string $e2e_id The end-to-end identifier for the PIX transaction.
     * @return Pix A Pix object containing the details of the retrieved PIX transaction.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrievePix(string $e2e_id): Pix
    {
        if ($this->pix_client === null) {
            $this->pix_client = new PixClient();
        }

        return $this->pix_client->retrievePixTransaction($this->config, $e2e_id);
    }

    /**
     * Retrieves a list of callback responses for a specified period, optionally filtered.
     *
     * @param string $initial_date_hour The starting date and hour for the retrieval of callbacks. Format: YYYY-MM-DD HH:mm.
     * @param string $final_date_hour The ending date and hour for the retrieval of callbacks. Format: YYYY-MM-DD HH:mm.
     * @param CallbackRetrieveFilter|null $filter The filter criteria for retrieving the callback responses.
     * @return RetrieveCallbackResponse[] A list of RetrieveCallbackResponse objects containing the details of the retrieved callbacks.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveCallbacksInRange(string $initial_date_hour, string $final_date_hour, ?CallbackRetrieveFilter $filter): array
    {
        if ($this->pix_webhook_sdk === null) {
            $this->pix_webhook_sdk = new PixWebhookClient();
        }

        return $this->pix_webhook_sdk->retrieveCallbacksInRange($this->config, $initial_date_hour, $final_date_hour, $filter);
    }

    /**
     * Retrieves a paginated list of callback responses for a specified period, optionally filtered.
     *
     * @param string $initial_date_hour The starting date and hour for the retrieval of callbacks. Format: YYYY-MM-DD HH:mm.
     * @param string $final_date_hour The ending date and hour for the retrieval of callbacks. Format: YYYY-MM-DD HH:mm.
     * @param int $page The page number for pagination.
     * @param int $page_size The number of items per page. If null, a default size will be used.
     * @param CallbackRetrieveFilter|null $filter The filter criteria for retrieving the callback responses.
     * @return PixCallbackPage A PixCallbackPage object containing the paginated list of retrieved callbacks.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveCallbacksPage(string $initial_date_hour, string $final_date_hour, int $page, int $page_size, ?CallbackRetrieveFilter $filter): PixCallbackPage
    {
        if ($this->pix_webhook_sdk === null) {
            $this->pix_webhook_sdk = new PixWebhookClient();
        }

        return $this->pix_webhook_sdk->retrieveCallbacksPage($this->config, $initial_date_hour, $final_date_hour, $page, $page_size, $filter);
    }

    /**
     * Includes a new webhook for a specified key.
     *
     * @param string $key The identifier key for which the webhook is being included.
     * @param string $webhook_url The URL of the webhook to be included.
     * @throws SdkException If an error occurs during the inclusion of the webhook.
     */
    public function includeWebhook(string $key, string $webhook_url): void
    {
        if ($this->pix_webhook_sdk === null) {
            $this->pix_webhook_sdk = new PixWebhookClient();
        }

        $this->pix_webhook_sdk->includeWebhook($this->config, $key, $webhook_url);
    }

    /**
     * Retrieves the details of a specific webhook by its identifier key.
     *
     * @param string $key The identifier key for the webhook to be retrieved.
     * @return Webhook A Webhook object containing the details of the retrieved webhook.
     * @throws SdkException If an error occurs during the retrieval process.
     */
    public function retrieveWebhook(string $key): Webhook
    {
        if ($this->pix_webhook_sdk === null) {
            $this->pix_webhook_sdk = new PixWebhookClient();
        }

        return $this->pix_webhook_sdk->retrieveWebhook($this->config, $key);
    }

    /**
     * Deletes a specific webhook identified by its key.
     *
     * @param string $key The identifier key for the webhook to be deleted.
     * @throws SdkException If an error occurs during the deletion process.
     */
    public function deleteWebhook(string $key): void
    {
        if ($this->pix_webhook_sdk === null) {
            $this->pix_webhook_sdk = new PixWebhookClient();
        }

        $this->pix_webhook_sdk->deleteWebhook($this->config, $key);
    }
}