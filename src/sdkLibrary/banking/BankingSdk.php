<?php

namespace Inter\Sdk\sdkLibrary\banking;

use Inter\Sdk\sdkLibrary\banking\balance\BalanceClient;
use Inter\Sdk\sdkLibrary\banking\bankstatement\BankStatementClient;
use Inter\Sdk\sdkLibrary\banking\models\Balance;
use Inter\Sdk\sdkLibrary\banking\models\BankStatement;
use Inter\Sdk\sdkLibrary\banking\models\BatchItem;
use Inter\Sdk\sdkLibrary\banking\models\BatchProcessing;
use Inter\Sdk\sdkLibrary\banking\models\BilletPayment;
use Inter\Sdk\sdkLibrary\banking\models\CallbackPage;
use Inter\Sdk\sdkLibrary\banking\models\CallbackRetrieveFilter;
use Inter\Sdk\sdkLibrary\banking\models\DarfPayment;
use Inter\Sdk\sdkLibrary\banking\models\DarfPaymentResponse;
use Inter\Sdk\sdkLibrary\banking\models\DarfPaymentSearchFilter;
use Inter\Sdk\sdkLibrary\banking\models\EnrichedBankStatementPage;
use Inter\Sdk\sdkLibrary\banking\models\FilterRetrieveEnrichedStatement;
use Inter\Sdk\sdkLibrary\banking\models\IncludeBatchPaymentResponse;
use Inter\Sdk\sdkLibrary\banking\models\IncludeDarfPaymentResponse;
use Inter\Sdk\sdkLibrary\banking\models\IncludePaymentResponse;
use Inter\Sdk\sdkLibrary\banking\models\IncludePixResponse;
use Inter\Sdk\sdkLibrary\banking\models\Payment;
use Inter\Sdk\sdkLibrary\banking\models\PaymentSearchFilter;
use Inter\Sdk\sdkLibrary\banking\models\Pix;
use Inter\Sdk\sdkLibrary\banking\models\RetrieveCallbackResponse;
use Inter\Sdk\sdkLibrary\banking\models\RetrievePixResponse;
use Inter\Sdk\sdkLibrary\banking\payment\BankingPaymentClient;
use Inter\Sdk\sdkLibrary\banking\pix\BankingPixClient;
use Inter\Sdk\sdkLibrary\banking\webhooks\BankingWebhookClient;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Webhook;

class BankingSdk
{
    private Config $config;
    private ?BankStatementClient $bank_statement_client = null;
    private ?BalanceClient $balance_client = null;
    private ?BankingPaymentClient $banking_payment_client = null;
    private ?BankingPixClient $banking_pix_client = null;
    private ?BankingWebhookClient $banking_webhook_client = null;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Retrieves the statement for a specific period. The maximum period between the dates is 90 days.
     *
     * @param string $initial_date Starting date for the statement query in YYYY-MM-DD format.
     * @param string $final_date Ending date for the statement query in YYYY-MM-DD format.
     * @return BankStatement A list of transactions.
     * @throws SdkException
     */
    public function retrieveStatement(string $initial_date, string $final_date): BankStatement
    {
        if ($this->bank_statement_client === null) {
            $this->bank_statement_client = new BankStatementClient();
        }

        return $this->bank_statement_client->retrieveStatement($this->config, $initial_date, $final_date);
    }

    /**
     * Retrieves the statement in PDF format for a specific period. The maximum period between the dates is 90 days.
     *
     * @param string $initial_date Starting date for the statement export in YYYY-MM-DD format.
     * @param string $final_date Ending date for the statement export in YYYY-MM-DD format.
     * @param string $file PDF file path that will be saved.
     * @throws SdkException If there is an error during the PDF statement retrieval process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/extratoexport
     */
    public function retrieveStatementInPdf(string $initial_date, string $final_date, string $file): void
    {
        if ($this->bank_statement_client === null) {
            $this->bank_statement_client = new BankStatementClient();
        }

        $this->bank_statement_client->retrieveStatementInPdf($this->config, $initial_date, $final_date, $file);
    }

    /**
     * Retrieves enriched statements with detailed information about each transaction for a specific period. The maximum period between the dates is 90 days.
     *
     * @param string $initial_date Starting date for the statement export in YYYY-MM-DD format.
     * @param string $final_date Ending date for the statement export in YYYY-MM-DD format.
     * @param FilterRetrieveEnrichedStatement|null $filter_retrieve Filters for the query (optional, can be null).
     * @param int $page Page number starting from 0.
     * @param int $page_size Size of the page, default = 50.
     * @return EnrichedBankStatementPage A list of enriched transactions.
     *
     * See: https://developers.bancointer.com.br/v4/reference/extratocomplete-1
     * @throws SdkException
     */
    public function retrieveEnrichedStatement(string $initial_date, string $final_date, ?FilterRetrieveEnrichedStatement $filter_retrieve, int $page, int $page_size = 50): EnrichedBankStatementPage
    {
        if ($this->bank_statement_client === null) {
            $this->bank_statement_client = new BankStatementClient();
        }

        return $this->bank_statement_client->retrieveStatementPage($this->config, $initial_date, $final_date, $page, $page_size, $filter_retrieve);
    }

    /**
     * Retrieves enriched statements within a date range using the specified filters.
     *
     * @param string $initial_date Starting date for the query in YYYY-MM-DD format.
     * @param string $final_date Ending date for the query in YYYY-MM-DD format.
     * @param FilterRetrieveEnrichedStatement|null $filter Filters for the query (optional, can be null).
     * @return EnrichedBankStatementPage[] A list of enriched transactions.
     * @throws SdkException If there is an error during the enriched statement retrieval process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/extratocomplete
     */
    public function retrieveEnrichedStatementWithRange(string $initial_date, string $final_date, ?FilterRetrieveEnrichedStatement $filter): array
    {
        if ($this->bank_statement_client === null) {
            $this->bank_statement_client = new BankStatementClient();
        }

        return $this->bank_statement_client->retrieveStatementWithRange($this->config, $initial_date, $final_date, $filter);
    }

    /**
     * Retrieves enriched statements with detailed information about each transaction for a specific period. The maximum period between the dates is 90 days.
     *
     * @param string $initial_date Starting date for the statement export in YYYY-MM-DD format.
     * @param string $final_date Ending date for the statement export in YYYY-MM-DD format.
     * @param FilterRetrieveEnrichedStatement|null $filter Filters for the query (optional, can be null).
     * @param int $page Page number starting from 0.
     * @return EnrichedBankStatementPage A list of enriched transactions.
     *
     * See: https://developers.bancointer.com.br/v4/reference/extratocomplete-1
     * @throws SdkException
     */
    public function retrieveEnrichedStatementPage(string $initial_date, string $final_date, ?FilterRetrieveEnrichedStatement $filter, int $page): EnrichedBankStatementPage
    {
        if ($this->bank_statement_client === null) {
            $this->bank_statement_client = new BankStatementClient();
        }

        return $this->bank_statement_client->retrieveStatementPage($this->config, $initial_date, $final_date, $page, null, $filter);
    }

    /**
     * Retrieves the balance for a specific period.
     *
     * @param string $balance_date Date for querying the positional balance in YYYY-MM-DD format.
     * @return Balance An object containing the account balances as of the specified date.
     * @throws SdkException If there is an error during the balance retrieval process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/saldo-1
     */
    public function retrieveBalance(string $balance_date): Balance
    {
        if ($this->balance_client === null) {
            $this->balance_client = new BalanceClient();
        }

        return $this->balance_client->retrieveBalance($this->config, $balance_date);
    }

    /**
     * Method for including an immediate payment or scheduling the payment of a billet, agreement, or tax with a barcode.
     *
     * @param BilletPayment $payment Payment data.
     * @return IncludePaymentResponse An object containing quantity of approvers, payment status, transaction code, etc.
     * @throws SdkException If there is an error during the payment inclusion process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/pagarboleto
     */
    public function includePayment(BilletPayment $payment): IncludePaymentResponse
    {
        if ($this->banking_payment_client === null) {
            $this->banking_payment_client = new BankingPaymentClient();
        }

        return $this->banking_payment_client->includeBilletPayment($this->config, $payment);
    }

    /**
     * Retrieves information about billets payments.
     *
     * @param string $initial_date Starting date, according to the "filterDateBy" field. Accepted format: YYYY-MM-DD.
     * @param string $final_date Ending date, according to the "filterDateBy" field. Accepted format: YYYY-MM-DD.
     * @param PaymentSearchFilter|null $filter Filters for the query (optional, can be null).
     * @return Payment[] A list of payments.
     * @throws SdkException If there is an error during the payment retrieval process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/buscarinformacoespagamentos
     */
    public function retrievePayment(string $initial_date, string $final_date, ?PaymentSearchFilter $filter): array
    {
        if ($this->banking_payment_client === null) {
            $this->banking_payment_client = new BankingPaymentClient();
        }

        return $this->banking_payment_client->retrievePaymentListInRange($this->config, $initial_date, $final_date, $filter);
    }

    /**
     * Method for including an immediate DARF payment without a barcode.
     *
     * @param DarfPayment $payment Payment data.
     * @return IncludeDarfPaymentResponse An object containing authentication, operation number, return type, transaction code, etc.
     * @throws SdkException If there is an error during the DARF payment inclusion process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/pagamentosdarf-1
     */
    public function includeDarfPayment(DarfPayment $payment): IncludeDarfPaymentResponse
    {
        if ($this->banking_payment_client === null) {
            $this->banking_payment_client = new BankingPaymentClient();
        }

        return $this->banking_payment_client->includeDarfPayment($this->config, $payment);
    }

    /**
     * Retrieves information about DARF payments.
     *
     * @param string $initial_date Starting date, according to the "filterDateBy" field. Accepted format: YYYY-MM-DD.
     * @param string $final_date Ending date, according to the "filterDateBy" field. Accepted format: YYYY-MM-DD.
     * @param DarfPaymentSearchFilter|null $filter Filters for the query (optional, can be null).
     * @return DarfPaymentResponse[] A list of DARF payments.
     * @throws SdkException If there is an error during the DARF payment retrieval process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/buscarinformacoespagamentodarf
     */
    public function retrieveDarfPayments(string $initial_date, string $final_date, ?DarfPaymentSearchFilter $filter): array
    {
        if ($this->banking_payment_client === null) {
            $this->banking_payment_client = new BankingPaymentClient();
        }

        return $this->banking_payment_client->retrieveDarfList($this->config, $initial_date, $final_date, $filter);
    }

    /**
     * Inclusion of a batch of payments entered by the client.
     *
     * @param string $my_identifier Identifier for the batch for the client.
     * @param BatchItem[] $payments Payments to be processed.
     * @return IncludeBatchPaymentResponse Information regarding the batch processing.
     * @throws SdkException If there is an error during the batch payment inclusion process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/pagamentoslote
     */
    public function includeBatchPayment(string $my_identifier, array $payments): IncludeBatchPaymentResponse
    {
        if ($this->banking_payment_client === null) {
            $this->banking_payment_client = new BankingPaymentClient();
        }

        return $this->banking_payment_client->includeBatchPayment($this->config, $my_identifier, $payments);
    }

    /**
     * Retrieves a batch of payments entered by the client.
     *
     * @param string $batch_id Identifier for the batch.
     * @return BatchProcessing Information regarding the batch processing.
     * @throws SdkException If there is an error during the batch payment retrieval process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/buscarinformacoespagamentolote
     */
    public function retrievePaymentBatch(string $batch_id): BatchProcessing
    {
        if ($this->banking_payment_client === null) {
            $this->banking_payment_client = new BankingPaymentClient();
        }

        return $this->banking_payment_client->retrievePaymentBatch($this->config, $batch_id);
    }

    /**
     * Method for including a Pix payment/transfer using banking data or a key.
     *
     * @param Pix $pix Pix data.
     * @return IncludePixResponse An object containing endToEndId, etc.
     * @throws SdkException If there is an error during the Pix payment inclusion process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/realizarpagamentopix-1
     */
    public function includePix(Pix $pix): IncludePixResponse
    {
        if ($this->banking_pix_client === null) {
            $this->banking_pix_client = new BankingPixClient();
        }

        return $this->banking_pix_client->includePix($this->config, $pix);
    }

    /**
     * Method for retrieving a Pix payment/transfer.
     *
     * @param string $request_code Pix data.
     * @return RetrievePixResponse An object containing endToEndId, etc.
     * @throws SdkException If there is an error during the Pix retrieval process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/realizarpagamentopix-1
     */
    public function retrievePix(string $request_code): RetrievePixResponse
    {
        if ($this->banking_pix_client === null) {
            $this->banking_pix_client = new BankingPixClient();
        }

        return $this->banking_pix_client->retrievePix($this->config, $request_code);
    }

    /**
     * Method intended to create a webhook to receive notifications for confirmation of Pix payments (callbacks).
     *
     * @param string $webhook_type The type of the webhook.
     * @param string $webhook_url The client's HTTPS server URL.
     * @throws SdkException If there is an error during the webhook inclusion process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/webhookput
     */
    public function includeWebhook(string $webhook_type, string $webhook_url): void
    {
        if ($this->banking_webhook_client === null) {
            $this->banking_webhook_client = new BankingWebhookClient();
        }

        $this->banking_webhook_client->includeWebhook($this->config, $webhook_type, $webhook_url);
    }

    /**
     * Retrieve the registered webhook.
     *
     * @param string $webhook_type The type of the webhook.
     * @return Webhook The registered webhook.
     * @throws SdkException If there is an error during the retrieval process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/webhookget-3
     */
    public function retrieveWebhook(string $webhook_type): Webhook
    {
        if ($this->banking_webhook_client === null) {
            $this->banking_webhook_client = new BankingWebhookClient();
        }

        return $this->banking_webhook_client->retrieveWebhook($this->config, $webhook_type);
    }

    /**
     * Deletes the webhook.
     *
     * @param string $webhook_type The type of the webhook to delete.
     * @throws SdkException If there is an error during the deletion process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/webhookdelete-3
     */
    public function deleteWebhook(string $webhook_type): void
    {
        if ($this->banking_webhook_client === null) {
            $this->banking_webhook_client = new BankingWebhookClient();
        }

        $this->banking_webhook_client->deleteWebhook($this->config, $webhook_type);
    }

    /**
     * Retrieves a collection of callbacks for a specific period, according to the provided parameters, without pagination.
     *
     * @param string $webhook_type The type of the webhook.
     * @param string $initial_date_hour Starting date, accepted format: YYYY-MM-DD.
     * @param string $final_date_hour Ending date, accepted format: YYYY-MM-DD.
     * @param CallbackRetrieveFilter|null $filter Filters for the query (optional, can be null).
     * @return RetrieveCallbackResponse[] A list of callback responses.
     * @throws SdkException If there is an error during the retrieval process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/pesquisarboletos
     */
    public function retrieveCallback(string $webhook_type, string $initial_date_hour, string $final_date_hour, ?CallbackRetrieveFilter $filter): array
    {
        if ($this->banking_webhook_client === null) {
            $this->banking_webhook_client = new BankingWebhookClient();
        }

        return $this->banking_webhook_client->retrieveCallbacksInRange($this->config, $webhook_type, $initial_date_hour, $final_date_hour, $filter);
    }

    /**
     * Retrieves a collection of callbacks for a specific period, according to the provided parameters, with pagination.
     *
     * @param string $webhook_type The type of the webhook.
     * @param string $initial_date_hour Starting date, accepted format: YYYY-MM-DD.
     * @param string $final_date_hour Ending date, accepted format: YYYY-MM-DD.
     * @param CallbackRetrieveFilter|null $filter Filters for the query (optional, can be null).
     * @param int $page The page number to retrieve.
     * @param int $page_size The number of items per page.
     * @return CallbackPage A paginated response containing callbacks.
     * @throws SdkException If there is an error during the retrieval process.
     *
     * See: https://developers.bancointer.com.br/v4/reference/pesquisarboletos
     */
    public function retrieveCallbackPage(string $webhook_type, string $initial_date_hour, string $final_date_hour, ?CallbackRetrieveFilter $filter, int $page = 1, int $page_size = 10): CallbackPage
    {
        if ($this->banking_webhook_client === null) {
            $this->banking_webhook_client = new BankingWebhookClient();
        }

        return $this->banking_webhook_client->retrieveCallbacksPage($this->config, $webhook_type, $initial_date_hour, $final_date_hour, $page, $page_size, $filter);
    }

    /**
     * Cancels the scheduling of a payment.
     *
     * @param string $transaction_code Unique transaction code.
     * @throws SdkException If there is an error during the cancellation process.
     */
    public function paymentSchedulingCancel(string $transaction_code): void
    {
        if ($this->banking_payment_client === null) {
            $this->banking_payment_client = new BankingPaymentClient();
        }

        $this->banking_payment_client->cancel($this->config, $transaction_code);
    }
}