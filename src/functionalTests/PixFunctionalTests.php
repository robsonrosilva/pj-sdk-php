<?php

namespace Inter\Sdk\functionalTests;

use DateMalformedStringException;
use Inter\Sdk\functionalTests\utils\FuncTestUtils;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\pix\enums\DevolutionNature;
use Inter\Sdk\sdkLibrary\pix\enums\ImmediateBillingType;
use Inter\Sdk\sdkLibrary\pix\models\Calendar;
use Inter\Sdk\sdkLibrary\pix\models\CallbackRetrieveFilter;
use Inter\Sdk\sdkLibrary\pix\models\Debtor;
use Inter\Sdk\sdkLibrary\pix\models\DevolutionRequestBody;
use Inter\Sdk\sdkLibrary\pix\models\DueBilling;
use Inter\Sdk\sdkLibrary\pix\models\DueBillingCalendar;
use Inter\Sdk\sdkLibrary\pix\models\DueBillingValue;
use Inter\Sdk\sdkLibrary\pix\models\IncludeDueBillingBatchRequest;
use Inter\Sdk\sdkLibrary\pix\models\PixBilling;
use Inter\Sdk\sdkLibrary\pix\models\PixValue;
use Inter\Sdk\sdkLibrary\pix\models\RetrievedPixFilter;
use Inter\Sdk\sdkLibrary\pix\models\RetrieveDueBillingFilter;
use Inter\Sdk\sdkLibrary\pix\models\RetrieveImmediateBillingsFilter;
use Inter\Sdk\sdkLibrary\pix\models\RetrieveLocationFilter;
use Inter\Sdk\sdkLibrary\pix\PixSdk;
use JsonException;

class PixFunctionalTests
{
    private PixSdk $pix_sdk;

    public function __construct($inter_sdk)
    {
        $this->pix_sdk = $inter_sdk->pix();
    }

    /**
     * Includes a due billing request for PIX payments.
     *
     * @throws SdkException If an error occurs during the inclusion of the due billing.
     */
    public function testPixIncludeDueBilling(): void
    {
        echo "Include due billing:\n";

        $document = FuncTestUtils::getString("cnpj");
        $name = FuncTestUtils::getString("name");
        $city = FuncTestUtils::getString("city");
        $street = FuncTestUtils::getString("street");
        $cep = FuncTestUtils::getString("cep");
        $email = FuncTestUtils::getString("email");
        $state = strtoupper(FuncTestUtils::getString("state"));
        $value = FuncTestUtils::getString("value(99.99)");
        $key = FuncTestUtils::getString("key");
        $tx_id = FuncTestUtils::getString("txId");
        $due_date = FuncTestUtils::getString("dueDate (yyyy-MM-dd)");
        $validity = FuncTestUtils::getString("validity (days)");

        $debtor = new Debtor(
            cnpj: $document,
            name: $name,
            email: $email,
            city: $city,
            state: $state,
            postal_code: $cep,
            address: $street,
        );

        $validity_after_expiration = (int) $validity;

        $due_billing_value = new DueBillingValue(
            original_value: $value
        );
        $calendar = new DueBillingCalendar(
            validity_after_expiration: $validity_after_expiration,
            due_date: $due_date,
        );
        $due_billing = new DueBilling(
            key: $key,
            debtor: $debtor,
            value: $due_billing_value,
            calendar: $calendar
        );

        $generated_immediate_billing = $this->pix_sdk->includeDuePixBilling($tx_id, $due_billing);
        echo json_encode($generated_immediate_billing->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves detailed information for a specific due billing request by transaction ID.
     *
     * @throws SdkException If an error occurs during the retrieval of the due billing.
     */
    public function testPixRetrieveDueBilling(): void
    {
        echo "Retrieving due billing:\n";

        $tx_id = FuncTestUtils::getString("txId");

        $detailed_due_pix_billing = $this->pix_sdk->retrieveDuePixBilling($tx_id);
        echo json_encode($detailed_due_pix_billing->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a collection of due billing records within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the due billing collection.
     */
    public function testPixRetrieveDueBillingCollection(): void
    {
        echo "Retrieving due billing collection:\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $retrieve_due_billing_filter = new RetrieveDueBillingFilter();

        $due_pix_billing_list = $this->pix_sdk->retrieveDueBillingCollectionInRange($initial_date, $final_date, $retrieve_due_billing_filter);
        $due_pix_list_dict = array_map(fn($due_pix_billing) => $due_pix_billing->toArray(), $due_pix_billing_list);
        echo json_encode($due_pix_list_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a paginated collection of due billing records within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the due billing collection page.
     */
    public function testPixRetrieveDueBillingCollectionPage(): void
    {
        echo "Retrieving due billing collection page:\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $retrieve_due_billing_filter = new RetrieveDueBillingFilter();
        $page = 0;
        $page_size = 10;

        $due_billing_page = $this->pix_sdk->retrieveDueBillingCollectionPage($initial_date, $final_date, $page, $page_size, $retrieve_due_billing_filter);
        echo json_encode($due_billing_page->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Reviews a due billing request by transaction ID.
     *
     * @throws SdkException If an error occurs during the review of the due billing.
     */
    public function testPixReviewDueBilling(): void
    {
        echo "Review due billing:\n";
        $tx_id = FuncTestUtils::getString("txId");
        $document = FuncTestUtils::getString("cnpj");
        $name = FuncTestUtils::getString("debtor name");
        $value = FuncTestUtils::getString("value(99.99)");
        $debtor = new Debtor(
            cnpj: $document,
            name: $name
        );
        $due_billing_value = new DueBillingValue(
            original_value: $value
        );
        $due_billing = new DueBilling(
            debtor: $debtor,
            value: $due_billing_value
        );
        $generated_due_billing = $this->pix_sdk->reviewDuePixBilling($tx_id, $due_billing);
        echo json_encode($generated_due_billing->toArray(), JSON_PRETTY_PRINT);
    }
    /**
     * Includes a batch of due billing requests for PIX payments.
     *
     * @throws SdkException If an error occurs during the inclusion of the due billing batch.
     */
    public function testPixIncludeDueBillingBatch(): void
    {
        echo "Include due billing batch:\n";
        $batch_description = FuncTestUtils::getString("batch description");
        $batch_id = FuncTestUtils::getString("batchId");
        $document = FuncTestUtils::getString("cpf");
        $name = FuncTestUtils::getString("debtor name");
        $first_tx_id = FuncTestUtils::getString("First billing txId");
        $first_value = FuncTestUtils::getString("First billing value(99.99)");
        $first_key = FuncTestUtils::getString("First billing key");
        $first_due_billing_value = new DueBillingValue(original_value: $first_value);
        $first_calendar = new DueBillingCalendar(
            due_date: FuncTestUtils::getString("First billing dueDate (yyyy-MM-dd)")
        );
        $second_tx_id = FuncTestUtils::getString("Second billing txId");
        $second_value = FuncTestUtils::getString("Second billing value(99.99)");
        $second_key = FuncTestUtils::getString("Second billing key");
        $second_due_billing_value = new DueBillingValue(original_value: $second_value);
        $second_calendar = new DueBillingCalendar(
            due_date: FuncTestUtils::getString("Second billing dueDate (yyyy-MM-dd)")
        );
        $debtor = new Debtor(cpf: $document, name: $name);
        $due_billing1 = new DueBilling(
            key: $first_key,
            debtor: $debtor,
            value: $first_due_billing_value,
            calendar: $first_calendar,
            txid: $first_tx_id,
        );
        $due_billing2 = new DueBilling(
            key: $second_key,
            debtor: $debtor,
            value: $second_due_billing_value,
            calendar: $second_calendar,
            txid: $second_tx_id,
        );
        $due_billing_list = [$due_billing1, $due_billing2];
        $batch = new IncludeDueBillingBatchRequest(
            description: $batch_description,
            due_billings: $due_billing_list
        );
        $this->pix_sdk->includeDueBillingBatch($batch_id, $batch);
        echo "Batch included: " . $batch_id . "\n";
    }

    /**
     * Retrieves a specific due billing batch by batch ID.
     *
     * @throws SdkException If an error occurs during the retrieval of the due billing batch.
     * @throws JsonException
     */
    public function testPixRetrieveDueBillingBatch(): void
    {
        echo "Retrieving due billing batch...\n";

        $batch_id = FuncTestUtils::getString("batchId");

        $due_billing_batch = $this->pix_sdk->retrieveDueBillingBatch($batch_id);
        echo json_encode($due_billing_batch->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a paginated collection of due billing batches within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the due billing batch collection.
     * @throws JsonException
     */
    public function testPixRetrieveDueBillingBatchCollectionPage(): void
    {
        echo "Retrieving due billing batch collection...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $page = 0;
        $page_size = 10;

        $due_billing_batch_page = $this->pix_sdk->retrieveDueBillingBatchCollectionPage($initial_date, $final_date, $page, $page_size);
        echo json_encode($due_billing_batch_page->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a collection of due billing batches within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the due billing batch collection.
     * @throws JsonException
     */
    public function testPixRetrieveDueBillingBatchCollection(): void
    {
        echo "Retrieving due billing batch collection...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");

        $due_billing_batch_collection = $this->pix_sdk->retrieveDueBillingBatchCollectionInRange($initial_date, $final_date);
        $due_billing_batch_collection_dict = array_map(fn($due_billing_batch) => $due_billing_batch->toArray(), $due_billing_batch_collection);
        echo json_encode($due_billing_batch_collection_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a due billing batch by its situation.
     *
     * @throws SdkException|JsonException If an error occurs during the retrieval of the due billing batch by situation.
     */
    public function testPixRetrieveDueBillingBatchBySituation(): void
    {
        echo "Retrieving due billing batch by situation...\n";

        $batch_id = FuncTestUtils::getString("batchId");
        $situation = FuncTestUtils::getString("batch situation: (EM_PROCESSAMENTO, CRIADA, NEGADA)");

        $due_billing_batch = $this->pix_sdk->retrieveDueBillingBatchBySituation($batch_id, $situation);
        echo json_encode($due_billing_batch->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a summary of a due billing batch by batch ID.
     *
     * @throws SdkException If an error occurs during the retrieval of the due billing batch summary.
     */
    public function testPixRetrieveDueBillingBatchSummary(): void
    {
        echo "Retrieving due billing batch summary...\n";

        $batch_id = FuncTestUtils::getString("batchId");

        $due_billing_batch_summary = $this->pix_sdk->retrieveDueBillingBatchSummary($batch_id);
        echo json_encode($due_billing_batch_summary->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Reviews a due billing batch by batch ID.
     *
     * @throws SdkException If an error occurs during the review of the due billing batch.
     */
    public function testPixReviewDueBillingBatch(): void
    {
        echo "Reviewing due billing batch...\n";

        $batch_description = FuncTestUtils::getString("batch description");
        $batch_id = FuncTestUtils::getString("batchId");

        $document = FuncTestUtils::getString("cpf");
        $name = FuncTestUtils::getString("debtor name");

        $first_tx_id = FuncTestUtils::getString("First billing txId");
        $first_value = FuncTestUtils::getString("First billing value(99.99)");
        $first_key = FuncTestUtils::getString("First billing key");
        $first_due_billing_value = new DueBillingValue(original_value: $first_value);
        $first_calendar = new DueBillingCalendar(
            due_date: FuncTestUtils::getString("First billing dueDate (yyyy-MM-dd)")
        );

        $second_tx_id = FuncTestUtils::getString("Second billing txId");
        $second_value = FuncTestUtils::getString("Second billing value(99.99)");
        $second_key = FuncTestUtils::getString("Second billing key");
        $second_due_billing_value = new DueBillingValue(original_value: $second_value);
        $second_calendar = new DueBillingCalendar(
            due_date: FuncTestUtils::getString("Second billing dueDate (yyyy-MM-dd)")
        );

        $debtor = new Debtor(cpf: $document, name: $name);

        $due_billing1 = new DueBilling(
            key: $first_key,
            debtor: $debtor,
            value: $first_due_billing_value,
            calendar: $first_calendar,
            txid: $first_tx_id,
        );

        $due_billing2 = new DueBilling(
            key: $second_key,
            debtor: $debtor,
            value: $second_due_billing_value,
            calendar: $second_calendar,
            txid: $second_tx_id
        );

        $due_billing_list = [$due_billing1, $due_billing2];

        $batch = new IncludeDueBillingBatchRequest(
            description: $batch_description,
            due_billings: $due_billing_list
        );

        $this->pix_sdk->reviewDueBillingBatch($batch_id, $batch);
        echo "Due billing batch reviewed.\n";
    }

    /**
     * Includes an immediate billing request for PIX payments.
     *
     * @throws SdkException If an error occurs during the inclusion of the immediate billing.
     * @throws DateMalformedStringException
     */
    public function testPixIncludeImmediateBilling(): void
    {
        echo "Include immediate billing:\n";

        $document = FuncTestUtils::getString("cnpj");
        $name = FuncTestUtils::getString("name");
        $value = FuncTestUtils::getString("value(99.99)");
        $key = FuncTestUtils::getString("key");
        $expiration = 86400;

        $debtor = new Debtor(cnpj: $document, name: $name);
        $pix_value = new PixValue(original: $value);
        $calendar = new Calendar(expiration: $expiration);
        $pix_billing = new PixBilling(
            calendar: $calendar,
            debtor: $debtor,
            value: $pix_value,
            key: $key,
        );

        $generated_immediate_billing = $this->pix_sdk->includeImmediateBilling($pix_billing);
        echo json_encode($generated_immediate_billing->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Includes an immediate billing request for PIX payments with a transaction ID.
     *
     * @throws SdkException|DateMalformedStringException If an error occurs during the inclusion of the immediate billing.
     */
    public function testPixIncludeImmediateBillingTxId(): void
    {
        echo "Include immediate billing:\n";

        $tx_id = FuncTestUtils::getString("txId");
        $document = FuncTestUtils::getString("cnpj");
        $name = FuncTestUtils::getString("name");
        $value = FuncTestUtils::getString("value(99.99)");
        $key = FuncTestUtils::getString("key");
        $expiration = 86400;

        $debtor = new Debtor(cnpj: $document, name: $name);
        $pix_value = new PixValue(original: $value);
        $calendar = new Calendar(expiration: $expiration);
        $pix_billing = new PixBilling(
            txid: $tx_id,
            calendar: $calendar,
            debtor: $debtor,
            value: $pix_value,
            key: $key,
        );

        $generated_immediate_billing = $this->pix_sdk->includeImmediateBilling($pix_billing);
        echo json_encode($generated_immediate_billing->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves detailed information for a specific immediate billing request by transaction ID.
     *
     * @throws SdkException If an error occurs during the retrieval of the immediate billing.
     */
    public function testPixRetrieveImmediateBilling(): void
    {
        echo "Retrieving immediate billing...\n";

        $tx_id = FuncTestUtils::getString("txId");

        $detailed_immediate_pix_billing = $this->pix_sdk->retrieveImmediateBilling($tx_id);
        echo json_encode($detailed_immediate_pix_billing->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a list of immediate billings within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the immediate billing list.
     */
    public function testPixRetrieveImmediateBillingCollection(): void
    {
        echo "Retrieving immediate billing list...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $filter = new RetrieveImmediateBillingsFilter();

        $detailed_immediate_pix_billings = $this->pix_sdk->retrieveImmediateBillingList($initial_date, $final_date, $filter);
        $detailed_immediate_pix_billing_dict = array_map(fn($detailed_immediate_pix_billing) => $detailed_immediate_pix_billing->toArray(), $detailed_immediate_pix_billings);
        echo json_encode($detailed_immediate_pix_billing_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a paginated list of immediate billings within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the immediate billing collection page.
     */
    public function testPixRetrieveImmediateBillingCollectionPage(): void
    {
        echo "Retrieving immediate billing list...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $filter = new RetrieveImmediateBillingsFilter();
        $page = 0;
        $page_size = 10;

        $billing_page = $this->pix_sdk->retrieveImmediateBillingPage($initial_date, $final_date, $page, $page_size, $filter);
        echo json_encode($billing_page->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Reviews an immediate billing request for PIX payments.
     *
     * @throws SdkException If an error occurs during the review of the immediate billing.
     */
    public function testPixReviewImmediateBilling(): void
    {
        echo "Review immediate billing list:\n";

        $tx_id = FuncTestUtils::getString("txId");
        $document = FuncTestUtils::getString("cnpj");
        $name = FuncTestUtils::getString("name");
        $value = FuncTestUtils::getString("value(99.99)");
        $key = FuncTestUtils::getString("key");
        $expiration = 86400; // Expiração em segundos

        $debtor = new Debtor(cnpj: $document, name: $name);
        $pix_value = new PixValue(original: $value);
        $calendar = new Calendar(expiration: $expiration);
        $pix_billing = new PixBilling(
            txid: $tx_id,
            calendar: $calendar,
            debtor: $debtor,
            value: $pix_value,
            key: $key,
        );

        $generated_immediate_billing = $this->pix_sdk->reviewImmediateBilling($pix_billing);
        echo json_encode($generated_immediate_billing->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Includes a new location for PIX payment.
     *
     * @throws SdkException If an error occurs during the inclusion of the location.
     * @throws DateMalformedStringException
     */
    public function testPixIncludeLocation(): void
    {
        echo "Including location...\n";

        $cob_type = ImmediateBillingType::cob;

        $location = $this->pix_sdk->includeLocation($cob_type);
        echo json_encode($location->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a specific location by its ID.
     *
     * @throws SdkException If an error occurs during the retrieval of the location.
     */
    public function testPixRetrieveLocation(): void
    {
        echo "Retrieving location...\n";

        $location_id = FuncTestUtils::getString("locationId");

        $location = $this->pix_sdk->retrieveLocation($location_id);
        echo json_encode($location->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a list of locations within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the location list.
     */
    public function testPixRetrieveLocationList(): void
    {
        echo "Retrieving location list...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $filter = new RetrieveLocationFilter();

        $locations = $this->pix_sdk->retrieveLocationsList($initial_date, $final_date, $filter);
        $locations_dict = array_map(fn($location) => $location->toArray(), $locations);
        echo json_encode($locations_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a paginated list of locations within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the location list page.
     */
    public function testPixRetrieveLocationListPage(): void
    {
        echo "Retrieving location list page...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $filter = new RetrieveLocationFilter();
        $page = 0;
        $page_size = 10;

        $location_page = $this->pix_sdk->retrieveLocationsPage($initial_date, $final_date, $page, $page_size, $filter);
        echo json_encode($location_page->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Unlinks a specific location by its ID.
     *
     * @throws SdkException If an error occurs during the unlinking of the location.
     */
    public function testPixUnlinkLocation(): void
    {
        echo "Unlink location:\n";

        $location_id = FuncTestUtils::getString("locationId");

        $location = $this->pix_sdk->unlinkLocation($location_id);

        echo json_encode($location->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Requests a devolution for a specific transaction.
     *
     * @throws SdkException If an error occurs during the devolution request.
     */
    public function testPixRequestDevolution(): void
    {
        echo "Request devolution:\n";

        $e2e_id = FuncTestUtils::getString("e2eId");
        $devolution_identifier = FuncTestUtils::getString("devolutionIdentifier");
        $value = FuncTestUtils::getString("value(99.99)");
        $description = FuncTestUtils::getString("description");
        $devolution_nature = DevolutionNature::ORIGINAL;

        $devolution = new DevolutionRequestBody(
            value: $value,
            nature: $devolution_nature,
            description: $description
        );

        $detailed_devolution = $this->pix_sdk->requestDevolution($e2e_id, $devolution_identifier, $devolution);

        echo json_encode($detailed_devolution->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves detailed information about a specific devolution request.
     *
     * @throws SdkException If an error occurs during the retrieval of the devolution.
     */
    public function testPixRetrieveDevolution(): void
    {
        echo "Retrieving devolution...\n";

        $e2e_id = FuncTestUtils::getString("e2eId");
        $devolution_identifier = FuncTestUtils::getString("devolutionIdentifier");

        $detailed_devolution = $this->pix_sdk->retrieveDevolution($e2e_id, $devolution_identifier);

        echo json_encode($detailed_devolution->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a list of PIX transactions within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the PIX list.
     */
    public function testPixRetrievePixList(): void
    {
        echo "Retrieving pix list...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $filter = new RetrievedPixFilter();

        $detailed_pix_list = $this->pix_sdk->retrievePixList($initial_date, $final_date, $filter);
        $detailed_pix_list_dict = array_map(fn($pix) => $pix->toArray(), $detailed_pix_list);
        echo json_encode($detailed_pix_list_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a paginated list of PIX transactions within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the PIX list page.
     */
    public function testPixRetrievePixListPage(): void
    {
        echo "Retrieving pix list page...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $filter = new RetrievedPixFilter();
        $page = 0;
        $page_size = 10;

        $pix_page = $this->pix_sdk->retrievePixPage($initial_date, $final_date, $page, $page_size, $filter);

        echo json_encode($pix_page->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves detailed information about a specific PIX transaction by its end-to-end ID.
     *
     * @throws SdkException If an error occurs during the retrieval of the PIX transaction.
     */
    public function testPixRetrievePix(): void
    {
        echo "Retrieving pix...\n";

        $e2e_id = FuncTestUtils::getString("e2eId");

        $pix = $this->pix_sdk->retrievePix($e2e_id);

        echo json_encode($pix->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a list of callbacks within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the callbacks.
     */
    public function testBillingRetrieveCallbacks(): void
    {
        echo "Retrieving callbacks...\n";

        $initial_date_hour = FuncTestUtils::getString("initialDateHour(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date_hour = FuncTestUtils::getString("finalDateHour(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $filter = new CallbackRetrieveFilter();

        $callbacks = $this->pix_sdk->retrieveCallbacksInRange($initial_date_hour, $final_date_hour, $filter);
        $callbacks_dict = array_map(fn($callback) => $callback->toArray(), $callbacks);
        echo json_encode($callbacks_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a paginated list of callbacks within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the callback page.
     */
    public function testBillingRetrieveCallbacksPage(): void
    {
        echo "Retrieving callback page...\n";

        $initial_date_hour = FuncTestUtils::getString("initialDateHour(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date_hour = FuncTestUtils::getString("finalDateHour(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $filter = new CallbackRetrieveFilter();
        $page = 0;
        $page_size = 10;

        $callbacks_page = $this->pix_sdk->retrieveCallbacksPage($initial_date_hour, $final_date_hour, $page, $page_size, $filter);
        echo json_encode($callbacks_page->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Includes a new webhook for billing notifications.
     *
     * @throws SdkException If an error occurs during the inclusion of the webhook.
     */
    public function testBillingIncludeWebhook(): void
    {
        echo "Include webhook:\n";

        $webhook_url = FuncTestUtils::getString("webhookUrl");
        $key = FuncTestUtils::getString("key");

        $this->pix_sdk->includeWebhook($key, $webhook_url);
        echo "Webhook included.\n";
    }

    /**
     * Retrieves the webhook associated with a specific key.
     *
     * @throws SdkException If an error occurs during the retrieval of the webhook.
     */
    public function testBillingRetrieveWebhook(): void
    {
        echo "Retrieving webhook...\n";

        $key = FuncTestUtils::getString("key");

        $webhook = $this->pix_sdk->retrieveWebhook($key);
        echo json_encode($webhook->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Deletes a webhook associated with a specific key.
     *
     * @throws SdkException If an error occurs during the deletion of the webhook.
     */
    public function testBillingDeleteWebhook(): void
    {
        echo "Deleting webhook...\n";

        $key = FuncTestUtils::getString("key");

        $this->pix_sdk->deleteWebhook($key);
        echo "Webhook deleted.\n";
    }
}