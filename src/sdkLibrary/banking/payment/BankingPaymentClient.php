<?php

namespace Inter\Sdk\sdkLibrary\banking\payment;

use Exception;
use Inter\Sdk\sdkLibrary\banking\models\Batch;
use Inter\Sdk\sdkLibrary\banking\models\BatchItem;
use Inter\Sdk\sdkLibrary\banking\models\BatchProcessing;
use Inter\Sdk\sdkLibrary\banking\models\BilletBatch;
use Inter\Sdk\sdkLibrary\banking\models\BilletPayment;
use Inter\Sdk\sdkLibrary\banking\models\DarfPayment;
use Inter\Sdk\sdkLibrary\banking\models\DarfPaymentBatch;
use Inter\Sdk\sdkLibrary\banking\models\DarfPaymentResponse;
use Inter\Sdk\sdkLibrary\banking\models\DarfPaymentSearchFilter;
use Inter\Sdk\sdkLibrary\banking\models\IncludeBatchPaymentResponse;
use Inter\Sdk\sdkLibrary\banking\models\IncludeDarfPaymentResponse;
use Inter\Sdk\sdkLibrary\banking\models\IncludePaymentResponse;
use Inter\Sdk\sdkLibrary\banking\models\Payment;
use Inter\Sdk\sdkLibrary\banking\models\PaymentSearchFilter;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Inter\Sdk\sdkLibrary\commons\utils\HttpUtils;
use Inter\Sdk\sdkLibrary\commons\utils\UrlUtils;
use Monolog\Logger;

class BankingPaymentClient
{
    /**
     * Cancels a scheduled payment based on the provided transaction code.
     *
     * @param Config $config The configuration object containing the client's details and environment settings.
     * @param string $transactionCode The unique code associated with the transaction that is to be canceled.
     * @throws SdkException If an error occurs during the cancellation process.
     */
    public function cancel(Config $config, string $transactionCode): void
    {
        $log = new Logger('CancelPaymentScheduling');
        $log->info("CancelPaymentScheduling banking {$config->getClientId()} {$transactionCode}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_PAYMENT) . "/{$transactionCode}";

        HttpUtils::callDelete($config, $url, Constants::BILLET_PAYMENT_WRITE_SCOPE, "Error canceling payment scheduling");
    }

    /**
     * Includes a list of payments in a batch using the provided configuration and identifier.
     *
     * @param Config $config The configuration object containing the client's details and environment settings.
     * @param string $myIdentifier A unique identifier for the batch payment.
     * @param BatchItem[] $payments A list of BatchItem objects representing the payments to be included in the batch.
     * @return IncludeBatchPaymentResponse An object containing the response from the banking API regarding the batch inclusion.
     * @throws SdkException If an error occurs while including the payments.
     */
    public function includeBatchPayment(Config $config, string $myIdentifier, array $payments): IncludeBatchPaymentResponse
    {
        $log = new Logger('IncludeBatchPayment');
        $log->info("IncludeBatchPayment banking {$config->getClientId()} {$myIdentifier} " . count($payments));

        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_PAYMENT_BATCH);

        $request = new Batch($myIdentifier, $payments);

        try {
            $jsonRequest = $request->toJson();
            $jsonResponse = HttpUtils::callPost($config, $url, Constants::BATCH_PAYMENT_WRITE_SCOPE, "Error including payment in batch", $jsonRequest);
            return IncludeBatchPaymentResponse::fromJson(json_decode($jsonResponse, true));
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
     * Includes a DARF payment request using the provided configuration and payment data.
     *
     * @param Config $config The configuration object containing the client's details and environment settings.
     * @param DarfPayment $payment The DarfPayment object containing the payment details to be included in the request.
     * @return IncludeDarfPaymentResponse An object that contains the response from the banking API regarding the DARF payment inclusion.
     * @throws SdkException If an error occurs while including the DARF payment.
     */
    public function includeDarfPayment(Config $config, DarfPayment $payment): IncludeDarfPaymentResponse
    {
        $log = new Logger('IncludeDarfPayment');
        $log->info("IncludeDarfPayment banking {$config->getClientId()} {$payment->getRevenueCode()}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_PAYMENT_DARF);

        try {
            $jsonRequest = $payment->toJson();
            $jsonResponse = HttpUtils::callPost($config, $url, Constants::DARF_PAYMENT_WRITE_SCOPE, "Error including DARF payment", $jsonRequest);
            return IncludeDarfPaymentResponse::fromJson(json_decode($jsonResponse, true));
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
     * Includes a billet payment request using the provided configuration and payment data.
     *
     * @param Config $config The configuration object containing the client's details and environment settings.
     * @param BilletPayment $payment The BilletPayment object containing the payment details to be included in the request.
     * @return IncludePaymentResponse An object that contains the response from the banking API regarding the billet payment inclusion.
     * @throws SdkException If an error occurs while including the billet payment.
     */
    public function includeBilletPayment(Config $config, BilletPayment $payment): IncludePaymentResponse
    {
        $log = new Logger('IncludePayment');
        $log->info("IncludePayment banking {$config->getClientId()} {$payment->getBarcode()}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_PAYMENT);

        try {
            $jsonRequest = $payment->toJson();
            $jsonResponse = HttpUtils::callPost($config, $url, Constants::BILLET_PAYMENT_WRITE_SCOPE, "Error including payment", $jsonRequest);
            return IncludePaymentResponse::fromJson(json_decode($jsonResponse, true));
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
     * Retrieves a list of DARF payments based on the specified date range and filters.
     *
     * @param Config $config The configuration object containing the client's details and environment settings.
     * @param string $initialDate The starting date for the payment retrieval in the format accepted by the API (e.g. "YYYY-MM-DD").
     * @param string $finalDate The ending date for the payment retrieval in the same format as above.
     * @param DarfPaymentSearchFilter|null $filter An optional object that contains additional search criteria.
     * @return DarfPaymentResponse[] A list of objects representing the retrieved DARF payments.
     * @throws SdkException If an error occurs while retrieving the DARF payments.
     */
    public function retrieveDarfList(Config $config, string $initialDate, string $finalDate, ?DarfPaymentSearchFilter $filter): array
    {
        $log = new Logger('RetrieveDarfPayments');
        $log->info("RetrieveDarfPayments banking {$config->getClientId()} {$initialDate}-{$finalDate}");
        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_PAYMENT_DARF) . "?dataInicio=" . urlencode($initialDate) . "&dataFim=" . urlencode($finalDate) . $this->addDarfFilters($filter);
        $jsonResponse = HttpUtils::callGet($config, $url, Constants::BILLET_PAYMENT_READ_SCOPE, "Error retrieving DARF payment");
        try {
            return array_map(fn($item) => DarfPaymentResponse::fromJson($item), json_decode($jsonResponse, true));
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
     * Retrieves payment batch details for a given batch ID.
     *
     * @param Config $config The configuration object containing the client's details and environment settings.
     * @param string $batchId The unique identifier for the batch of payments to be retrieved.
     * @return BatchProcessing An object that contains the details of the payment batch along with the individual payments.
     * @throws SdkException If an error occurs while retrieving the payment batch.
     */
    public function retrievePaymentBatch(Config $config, string $batchId): BatchProcessing
    {
        $log = new Logger('RetrievePaymentBatch');
        $log->info("RetrievePaymentBatch {$config->getClientId()} {$batchId}");
        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_PAYMENT_BATCH) . "/{$batchId}";
        $response = HttpUtils::callGet($config, $url, Constants::BATCH_PAYMENT_READ_SCOPE, "Error retrieving batch");
        $jsonResponse = json_decode($response, true);
        try {
            $payments = [];
            if (!empty($jsonResponse["pagamentos"])) {
                foreach ($jsonResponse["pagamentos"] as $item) {
                    $paymentType = $item["tipoPagamento"] ?? null;
                    if ($paymentType === "BILLET") {
                        $billetBatch = BilletBatch::fromJson($item);
                        $payments[] = $billetBatch;
                    } else {
                        $darfBatch = DarfPaymentBatch::fromJson($item);
                        $payments[] = $darfBatch;
                    }
                }
                $jsonResponse["pagamentos"] = null;
            }
            $batchProcessing = BatchProcessing::fromJson(json_decode(json_encode($jsonResponse), true));
            $batchProcessing->setPayments($payments);
            return $batchProcessing;
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
     * Retrieves a list of payments based on the specified date range and filters.
     *
     * @param Config $config The configuration object containing the client's details and environment settings.
     * @param string $initialDate The starting date for the payment retrieval in the format accepted by the API (e.g. "YYYY-MM-DD").
     * @param string $finalDate The ending date for the payment retrieval in the same format as above.
     * @param PaymentSearchFilter|null $filter
     * @return Payment[] A list of objects representing the retrieved payments.
     * @throws SdkException If an error occurs while retrieving the payments.
     */
    public function retrievePaymentListInRange(Config $config, string $initialDate, string $finalDate, ?PaymentSearchFilter $filter): array
    {
        $log = new Logger('RetrievePayments');
        $log->info("RetrievePayments banking {$config->getClientId()} {$initialDate}-{$finalDate}");
        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_PAYMENT) .
            "?dataInicio=" . urlencode($initialDate) .
            "&dataFim=" . urlencode($finalDate) .
            $this->addPaymentFilters($filter);
        $jsonResponse = HttpUtils::callGet($config, $url, Constants::BILLET_PAYMENT_READ_SCOPE, "Error retrieving payments");
        try {
            return array_map(fn($item) => Payment::fromJson($item), json_decode($jsonResponse, true));
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
     * Adds filters to the request URL based on the provided DarfPaymentSearchFilter.
     *
     * @param DarfPaymentSearchFilter|null $filter The filter object containing optional filter criteria.
     * @return string A string of query parameters representing the filters, or an empty string if no filters are set.
     */
    public function addDarfFilters(?DarfPaymentSearchFilter $filter): string
    {
        if ($filter === null) {
            return "";
        }
        $filterParams = [];
        if ($filter->getRequestCode() !== null) {
            $filterParams[] = "&codigoTransacao={$filter->getRequestCode()}";
        }
        if ($filter->getRevenueCode() !== null) {
            $filterParams[] = "&codigoReceita={$filter->getRevenueCode()}";
        }
        if ($filter->getFilterDateBy() !== null) {
            $filterParams[] = "&filtrarDataPor={$filter->getFilterDateBy()->value}";
        }
        return implode('', $filterParams);
    }
    /**
     * Adds filters to the request URL based on the provided PaymentSearchFilter.
     *
     * @param PaymentSearchFilter|null $filter The filter object containing optional filter criteria.
     * @return string A string of query parameters representing the filters, or an empty string if no filters are set.
     */
    public function addPaymentFilters(?PaymentSearchFilter $filter): string
    {
        if ($filter === null) {
            return "";
        }
        $stringFilter = [];
        if ($filter->getBarcode() !== null) {
            $stringFilter[] = "&codBarraLinhaDigitavel={$filter->getBarcode()}";
        }
        if ($filter->getTransactionCode() !== null) {
            $stringFilter[] = "&codigoTransacao={$filter->getTransactionCode()}";
        }
        if ($filter->getFilterDateBy() !== null) {
            $stringFilter[] = "&filtrarDataPor={$filter->getFilterDateBy()->value}";
        }
        return implode('', $stringFilter);
    }
}