<?php

namespace Inter\Sdk\functionalTests;

use Inter\Sdk\functionalTests\utils\FuncTestUtils;
use Inter\Sdk\sdkLibrary\banking\BankingSdk;
use Inter\Sdk\sdkLibrary\banking\enums\DarfPaymentDateType;
use Inter\Sdk\sdkLibrary\banking\enums\OperationType;
use Inter\Sdk\sdkLibrary\banking\enums\PaymentDateType;
use Inter\Sdk\sdkLibrary\banking\models\BilletBatch;
use Inter\Sdk\sdkLibrary\banking\models\BilletPayment;
use Inter\Sdk\sdkLibrary\banking\models\CallbackRetrieveFilter;
use Inter\Sdk\sdkLibrary\banking\models\DarfPayment;
use Inter\Sdk\sdkLibrary\banking\models\DarfPaymentBatch;
use Inter\Sdk\sdkLibrary\banking\models\DarfPaymentSearchFilter;
use Inter\Sdk\sdkLibrary\banking\models\FilterRetrieveEnrichedStatement;
use Inter\Sdk\sdkLibrary\banking\models\Key;
use Inter\Sdk\sdkLibrary\banking\models\PaymentSearchFilter;
use Inter\Sdk\sdkLibrary\banking\models\Pix;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\InterSdk;
use JsonException;

class BankingFunctionalTests
{
    private BankingSdk $banking_sdk;

    public function __construct(InterSdk $inter_sdk)
    {
        $this->banking_sdk = $inter_sdk->banking();
    }

    /**
     * Retrieves and prints the banking statement for a specified period.
     *
     * @throws SdkException|JsonException If an error occurs during the retrieval of the statement.
     */
    public function testBankingStatement(): void
    {
        echo "Retrieving statement...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DD)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DD)");

        $statement = $this->banking_sdk->retrieveStatement($initial_date, $final_date);
        echo json_encode($statement->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves the banking statement in PDF format and saves it to a file.
     *
     * @throws SdkException If an error occurs during the retrieval of the statement.
     */
    public function testBankingStatementPdf(): void
    {
        echo "Retrieving statement in pdf...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DD)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DD)");
        $file = "statement.pdf";

        $this->banking_sdk->retrieveStatementInPdf($initial_date, $final_date, $file);
        echo "Generated file: $file\n";
    }

    /**
     * Retrieves the enriched banking statement for a specified period.
     *
     * @throws SdkException If an error occurs during the retrieval of the statement.
     */
    public function testBankingEnrichedStatement(): void
    {
        echo "Retrieving enriched statement...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DD)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DD)");
        $filter = new FilterRetrieveEnrichedStatement(OperationType::C->toString());

        $enriched_transactions = $this->banking_sdk->retrieveEnrichedStatementWithRange($initial_date, $final_date, $filter);
        $transactions_dict = array_map(fn($transaction) => $transaction->toArray(), $enriched_transactions);
        echo json_encode($transactions_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a page of enriched banking statements for a specified period.
     *
     * @throws SdkException
     */
    public function testBankingEnrichedStatementPage(): void
    {
        echo "Retrieving enriched statement page...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DD)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DD)");
        $filter_retrieve = new FilterRetrieveEnrichedStatement(OperationType::C->toString());
        $page = 0;
        $pagesize = 10;

        $enriched_transactions = $this->banking_sdk->retrieveEnrichedStatementPage($initial_date, $final_date, $filter_retrieve, $page, $pagesize);
        echo json_encode($enriched_transactions->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves the banking balance for a specified date.
     *
     * @throws SdkException If an error occurs during the retrieval of the balance.
     */
    public function testBankingBalance(): void
    {
        echo "Retrieving balance...\n";
        $balance = $this->banking_sdk->retrieveBalance("");
        echo json_encode($balance->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Includes a payment to be processed.
     *
     * @throws SdkException If an error occurs during the payment inclusion.
     */
    public function testBankingIncludePayment(): void
    {
        echo "Include payment:\n";

        $bar_code = FuncTestUtils::getString("barCode");
        $value = FuncTestUtils::getBigDecimal("value(99.99)");
        $due_date = FuncTestUtils::getString("dueDate(YYYY-MM-DD)");
        $payment_date = FuncTestUtils::getString("payment_date(YYYY-MM-DD)");

        $payment = new BilletPayment($bar_code, $value, $due_date, $payment_date);

        $payment_response = $this->banking_sdk->includePayment($payment);
        echo json_encode($payment_response->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Cancels a scheduled payment.
     *
     * @throws SdkException If an error occurs during the cancellation of the payment.
     */
    public function testBankingCancelPayment(): void
    {
        echo "Canceling payment:\n";

        $request_code = FuncTestUtils::getString("transactionCode");

        $this->banking_sdk->paymentSchedulingCancel($request_code);

        echo "Payment canceled.\n";
    }

    /**
     * Retrieves a list of payments for a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of the payment list.
     */
    public function testBankingRetrievePaymentList(): void
    {
        echo "Retrieving payment list...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DD)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DD)");
        $filter = new PaymentSearchFilter(
            filter_date_by: PaymentDateType::PAGAMENTO
        );

        $payments = $this->banking_sdk->retrievePayment($initial_date, $final_date, $filter);
        $payments_dict = array_map(fn($payment) => $payment->toArray(), $payments);
        echo json_encode($payments_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Includes a DARF payment.
     *
     * @throws SdkException If an error occurs during the inclusion of the DARF payment.
     */
    public function testBankingIncludeDarfPayment(): void
    {
        echo "Include DARF payment:\n";

        $document = FuncTestUtils::getString("document");
        $codigo_receita = FuncTestUtils::getString("codigoReceita");
        $due_date = FuncTestUtils::getString("dueDate(YYYY-MM-DD)");
        $description = FuncTestUtils::getString("description");
        $enterprise = FuncTestUtils::getString("enterprise");
        $calculation_period = FuncTestUtils::getString("calculationPeriod(YYYY-MM-DD)");
        $principal_value = FuncTestUtils::getString("principalValue(99.99)");
        $reference = FuncTestUtils::getString("reference");

        $darf_payment = new DarfPayment(
            cnpjOrCpf: $document,
            revenueCode: $codigo_receita,
            dueDate: $due_date,
            description: $description,
            enterpriseName: $enterprise,
            assessmentPeriod: $calculation_period,
            reference: $reference,
            principalValue: $principal_value
        );

        $darf_payment_response = $this->banking_sdk->includeDarfPayment($darf_payment);
        echo json_encode($darf_payment_response->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a list of DARF payments for a specified date range.
     *
     * @throws SdkException If an error occurs during the retrieval of DARF payments.
     */
    public function testBankingRetrieveDarfPayment(): void
    {
        echo "Retrieving DARF payment...\n";

        $initial_date = FuncTestUtils::getString("initialDate(YYYY-MM-DD)");
        $final_date = FuncTestUtils::getString("finalDate(YYYY-MM-DD)");
        $filter = new DarfPaymentSearchFilter(DarfPaymentDateType::PAGAMENTO->value);

        $retrieve_darf_payments = $this->banking_sdk->retrieveDarfPayments($initial_date, $final_date, $filter);
        $transactions_dict = array_map(fn($darf) => $darf->toArray(), $retrieve_darf_payments);
        echo json_encode($transactions_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Includes a batch of payments consisting of billet and DARF payments.
     *
     * @throws SdkException If an error occurs during the inclusion of the payment batch.
     */
    public function testBankingIncludePaymentBatch(): void
    {
        echo "Include batch of payments:\n";

        echo "Billet payment: \n";
        $bar_code = FuncTestUtils::getString("barCode");
        $billet_value = FuncTestUtils::getBigDecimal("billetValue");
        $billet_due_date = FuncTestUtils::getString("billetDueDate(YYYY-MM-DD)");

        echo "DARF payment: \n";
        $document = FuncTestUtils::getString("document(cpf)");
        $codigo_receita = FuncTestUtils::getString("codigoReceita");
        $darf_due_date = FuncTestUtils::getString("darfDueDate(YYYY-MM-DD)");
        $description = FuncTestUtils::getString("description");
        $enterprise = FuncTestUtils::getString("enterprise");
        $calculation_period = FuncTestUtils::getString("calculationPeriod");
        $darf_value = FuncTestUtils::getString("darfValue(99.99)");
        $reference = FuncTestUtils::getString("reference");
        $my_identifier = FuncTestUtils::getString("batch identifier");

        $billet_batch = new BilletBatch(
            barcode: $bar_code,
            amount_to_pay: $billet_value,
            due_date: $billet_due_date
        );

        $darf_batch = new DarfPaymentBatch(
            cnpj_or_cpf: $document,
            revenue_code: $codigo_receita,
            due_date: $darf_due_date,
            description: $description,
            enterprise_name:  $enterprise,
            assessment_period: $calculation_period,
            reference: $reference,
            principal_value: $darf_value,
        );

        $payments = [$billet_batch, $darf_batch];

        $batch_payment_response = $this->banking_sdk->includeBatchPayment($my_identifier, $payments);
        echo json_encode($batch_payment_response->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a batch of payments by its identifier.
     *
     * @throws SdkException If an error occurs during the retrieval of the payment batch.
     */
    public function testBankingRetrievePaymentBatch(): void
    {
        echo "Retrieving batch of payments...\n";

        $batch_id = FuncTestUtils::getString("batchId");

        $batch_processing = $this->banking_sdk->retrievePaymentBatch($batch_id);
        echo json_encode($batch_processing->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Includes a PIX payment.
     *
     * @throws SdkException If an error occurs during the inclusion of the PIX.
     */
    public function testBankingIncludePix(): void
    {
        echo "Include pix:\n";

        $key = FuncTestUtils::getString("key");
        $value = FuncTestUtils::getString("value(99.99)");

        $recipient = new Key(
            key: $key
        );

        $pix = new Pix(
            amount: $value,
            recipient: $recipient
        );

        $include_pix_response = $this->banking_sdk->includePix($pix);
        echo json_encode($include_pix_response->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a PIX payment by its request code.
     *
     * @throws SdkException If an error occurs during the retrieval of the PIX.
     */
    public function testBankingRetrievePix(): void
    {
        echo "Retrieving pix...\n";

        $request_code = FuncTestUtils::getString("requestCode");

        $retrieve_pix_response = $this->banking_sdk->retrievePix($request_code);
        echo json_encode($retrieve_pix_response->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Includes a webhook for payment notifications.
     *
     * @throws SdkException If an error occurs during the inclusion of the webhook.
     */
    public function testBankingIncludeWebhook(): void
    {
        echo "Include webhook:\n";

        $webhook_type = FuncTestUtils::getString("webhookType (pix-pagamento,boleto-pagamento)");
        $webhook_url = FuncTestUtils::getString("webhookUrl");

        $this->banking_sdk->includeWebhook($webhook_type, $webhook_url);
        echo "Webhook included.\n";
    }

    /**
     * Retrieves a webhook configuration by its type.
     *
     * @throws SdkException If an error occurs during the retrieval of the webhook.
     */
    public function testBankingRetrieveWebhook(): void
    {
        echo "Retrieving webhook...\n";

        $webhook_type = FuncTestUtils::getString("webhookType (pix-pagamento,boleto-pagamento)");

        $webhook = $this->banking_sdk->retrieveWebhook($webhook_type);
        echo json_encode($webhook->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * Deletes a specified webhook.
     *
     * @throws SdkException If an error occurs during the deletion of the webhook.
     */
    public function testBankingDeleteWebhook(): void
    {
        echo "Deleting webhook...\n";

        $webhook_type = FuncTestUtils::getString("webhookType (pix-pagamento,boleto-pagamento)");

        $this->banking_sdk->deleteWebhook($webhook_type);
        echo "Webhook deleted.\n";
    }

    /**
     * Retrieves a list of callbacks for a specified webhook type and date range.
     *
     * @throws SdkException If an error occurs during the retrieval of callbacks.
     */
    public function testBankingRetrieveCallbacks(): void
    {
        echo "Retrieving callbacks...\n";

        $webhook_type = FuncTestUtils::getString("webhookType (pix-pagamento,boleto-pagamento)");
        $initial_date_hour = FuncTestUtils::getString("initialDateHour(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date_hour = FuncTestUtils::getString("finalDateHour(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $filter = new CallbackRetrieveFilter();

        $callbacks = $this->banking_sdk->retrieveCallback($webhook_type, $initial_date_hour, $final_date_hour, $filter);
        $callbacks_dict = array_map(fn($callback) => $callback->toArray(), $callbacks);
        echo json_encode($callbacks_dict, JSON_PRETTY_PRINT);
    }

    /**
     * Retrieves a paginated list of callbacks for a specified webhook type and date range.
     *
     * @throws SdkException If an error occurs during the retrieval of callbacks.
     * @throws JsonException
     */
    public function testBankingRetrieveCallbackPaginated(): void
    {
        echo "Retrieving callbacks...\n";

        $webhook_type = FuncTestUtils::getString("webhookType (pix-pagamento,boleto-pagamento)");
        $initial_date_hour = FuncTestUtils::getString("initialDateHour(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $final_date_hour = FuncTestUtils::getString("finalDateHour(YYYY-MM-DDTHH:MM:SSZ ex:2022-04-01T10:30:00Z)");
        $filter = new CallbackRetrieveFilter();
        $page = 0;
        $page_size = 10;

        $callbacks_page = $this->banking_sdk->retrieveCallbackPage($webhook_type, $initial_date_hour, $final_date_hour, $filter, $page, $page_size);
        echo json_encode($callbacks_page->toArray(), JSON_PRETTY_PRINT);
    }
}