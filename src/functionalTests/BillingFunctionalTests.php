<?php

namespace Inter\Sdk\functionalTests;

use Inter\Sdk\functionalTests\utils\FuncTestUtils;
use Inter\Sdk\sdkLibrary\billing\BillingSdk;
use Inter\Sdk\sdkLibrary\billing\enums\PersonType;
use Inter\Sdk\sdkLibrary\billing\models\BillingIssueRequest;
use Inter\Sdk\sdkLibrary\billing\models\BillingRetrievalFilter;
use Inter\Sdk\sdkLibrary\billing\models\BillingRetrieveCallbacksFilter;
use Inter\Sdk\sdkLibrary\billing\models\Person;
use Inter\Sdk\sdkLibrary\billing\models\Sorting;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;

class BillingFunctionalTests
{
    private BillingSdk $billing_sdk;

    public function __construct($inter_sdk)
    {
        $this->billing_sdk = $inter_sdk->billing();
    }

    /**
     * Issues a billing request for a specified payer and amount.
     *
     * @throws SdkException If an error occurs during the billing process.
     */
    public function testBillingIssueBilling(): void
    {
        echo "Include billing:\n";

        $person_type = PersonType::FISICA;

        $your_number = FuncTestUtils::getString("yourNumber");
        $due_date = FuncTestUtils::getString("dueDate(YYYY-MM-DD)");
        $value = FuncTestUtils::getBigDecimal("value(99.99)");

        echo "Payer data:\n";
        $document = FuncTestUtils::getString("cpf");
        $name = FuncTestUtils::getString("name");
        $street = FuncTestUtils::getString("street");
        $city = FuncTestUtils::getString("city");
        $state = strtoupper(FuncTestUtils::getString("state"));
        $cep = FuncTestUtils::getString("cep");

        $payer = new Person(
            cpf_cnpj: $document,
            person_type: $person_type,
            name: $name,
            address: $street,
            city: $city,
            state: $state,
            zip_code: $cep
        );

        $billing = new BillingIssueRequest(
            your_number: $your_number,
            nominal_value: $value,
            due_date: $due_date,
            scheduled_days: 0,
            payer: $payer
        );

        $billing_issue_response = $this->billing_sdk->issueBilling($billing);
        echo json_encode($billing_issue_response->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Cancels a billing request using the specified request code and reason.
     *
     * @throws SdkException If an error occurs during the cancellation of the billing.
     */
    public function testBillingCancelBilling(): void
    {
        echo "Cancel billing:\n";

        $request_code = FuncTestUtils::getString("requestCode");
        $cancellation_reason = FuncTestUtils::getString("cancellationReason");

        $this->billing_sdk->cancelBilling($request_code, $cancellation_reason);
        echo "Billing canceled.\n";
    }

    /**
     * Retrieves billing information for a specified request code.
     *
     * @throws SdkException If an error occurs during the retrieval of billing.
     */
    public function testBillingRetrieveBilling(): void
    {
        echo "Retrieving billing...\n";

        $request_code = FuncTestUtils::getString("requestCode");

        $retrieved_billing = $this->billing_sdk->retrieveBilling($request_code);
        echo json_encode($retrieved_billing->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a collection of billing records within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of billing collection.
     */
    public function testBillingRetrieveBillingCollection(): void
    {
        echo "Retrieving billing collection...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DD)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DD)");
        $billing_retrieval_filter = new BillingRetrievalFilter();
        $sorting = new Sorting();

        $retrieve_billings = $this->billing_sdk->retrieveBillingCollection($initial_date, $final_date, $billing_retrieval_filter, $sorting);
        $billings_dict = array_map(fn($billing) => $billing->toArray(), $retrieve_billings);
        echo json_encode($billings_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a paginated collection of billing records within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of billing collection.
     */
    public function testBillingRetrieveBillingCollectionPage(): void
    {
        echo "Retrieving billing collection page...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DD)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DD)");
        $billing_retrieval_filter = new BillingRetrievalFilter();
        $page = 0;
        $page_size = 10;
        $sorting = new Sorting();

        $billing_page = $this->billing_sdk->retrieveBillingCollectionPage($initial_date, $final_date, $page, $page_size, $billing_retrieval_filter, $sorting);
        echo json_encode($billing_page->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a billing document in PDF format.
     *
     * @throws SdkException If an error occurs during the retrieval of the billing PDF.
     */
    public function testBillingRetrieveBillingPdf(): void
    {
        echo "Retrieving billing in PDF...\n";

        $request_code = FuncTestUtils::getString("requestCode");
        $file = "file_" . $request_code . ".pdf";

        $this->billing_sdk->retrieveBillingPdf($request_code, $file);
        echo "Generated file: " . $file . "\n";
    }

    /**
     * Retrieves a summary of billing records within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the billing summary.
     */
    public function testBillingRetrieveBillingSummary(): void
    {
        echo "Retrieving billing summary...\n";

        $initial_date = FuncTestUtils::getString("initialDate");
        $final_date = FuncTestUtils::getString("finalDate");
        $billing_retrieval_filter = new BillingRetrievalFilter();

        $summary_list = $this->billing_sdk->retrieveBillingSummary($initial_date, $final_date, $billing_retrieval_filter);
        $summary_dict = array_map(fn($summary) => $summary->toArray(), $summary_list);
        echo json_encode($summary_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a list of billing callbacks within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of callbacks.
     */
    public function testBillingRetrieveCallbacks(): void
    {
        echo "Retrieving callbacks...\n";

        $initial_date_hour = FuncTestUtils::getString("initialDateHour(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date_hour = FuncTestUtils::getString("finalDateHour(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $filter = new BillingRetrieveCallbacksFilter();
        $page_size = 50;

        $callbacks = $this->billing_sdk->retrieveCallbacks($initial_date_hour, $final_date_hour, $filter, $page_size);
        $callback_dict = array_map(fn($callback) => $callback->toArray(), $callbacks);
        echo json_encode($callback_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a paginated list of billing callbacks within a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of callbacks.
     */
    public function testBillingRetrieveCallbacksPage(): void
    {
        echo "Retrieving callback page...\n";

        $initial_date_hour = FuncTestUtils::getString("initialDateHour(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date_hour = FuncTestUtils::getString("finalDateHour(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $filter = new BillingRetrieveCallbacksFilter();
        $page = 0;
        $page_size = 10;

        $callback = $this->billing_sdk->retrieveCallbacksPage($initial_date_hour, $final_date_hour, $page, $page_size, $filter);
        echo json_encode($callback->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Includes a webhook for billing notifications.
     *
     * @throws SdkException If an error occurs during the inclusion of the webhook.
     */
    public function testBillingIncludeWebhook(): void
    {
        echo "Include webhook:\n";

        $webhook_url = FuncTestUtils::getString("webhookUrl");

        $this->billing_sdk->includeWebhook($webhook_url);
        echo "Webhook included.\n";
    }

    /**
     * Retrieves the current webhook configuration.
     *
     * @throws SdkException If an error occurs during the retrieval of the webhook.
     */
    public function testBillingRetrieveWebhook(): void
    {
        echo "Retrieving webhook...\n";

        $webhook = $this->billing_sdk->retrieveWebhook();
        echo json_encode($webhook->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Deletes the current webhook configuration.
     *
     * @throws SdkException If an error occurs during the deletion of the webhook.
     */
    public function testBillingDeleteWebhook(): void
    {
        echo "Deleting webhook...\n";

        $this->billing_sdk->deleteWebhook();
        echo "Webhook deleted.\n";
    }
}