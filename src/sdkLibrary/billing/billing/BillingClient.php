<?php

namespace Inter\Sdk\sdkLibrary\billing\billing;

use Exception;
use Inter\Sdk\sdkLibrary\billing\models\BillingIssueRequest;
use Inter\Sdk\sdkLibrary\billing\models\BillingIssueResponse;
use Inter\Sdk\sdkLibrary\billing\models\BillingPage;
use Inter\Sdk\sdkLibrary\billing\models\BillingRetrievalFilter;
use Inter\Sdk\sdkLibrary\billing\models\CancelBillingRequest;
use Inter\Sdk\sdkLibrary\billing\models\RetrievedBilling;
use Inter\Sdk\sdkLibrary\billing\models\Sorting;
use Inter\Sdk\sdkLibrary\billing\models\SummaryItem;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\models\PdfReturn;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Inter\Sdk\sdkLibrary\commons\utils\HttpUtils;
use Inter\Sdk\sdkLibrary\commons\utils\UrlUtils;
use Monolog\Logger;

class BillingClient
{
    /**
     * Cancels a billing request identified by its request code.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $requestCode The unique identifier for the billing request to be canceled.
     * @param string $cancellationReason The reason for canceling the billing request.
     * @throws SdkException If there is an error during the cancellation process.
     */
    public function cancelBilling(Config $config, string $requestCode, string $cancellationReason): void
    {
        $log = new Logger('CancelBilling');
        $log->info("CancelBilling {$config->getClientId()} {$requestCode} {$cancellationReason}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BILLING) . "/{$requestCode}/cancelar";

        $request = new CancelBillingRequest($cancellationReason);

        try {
            $jsonRequest = $request->toJson();
            HttpUtils::callPost($config, $url, Constants::BILLET_BILLING_WRITE_SCOPE, "Error canceling billing", $jsonRequest);
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
     * Issues a new billing request based on the provided billing issue details.
     *
     * @param Config $config The configuration object containing client information.
     * @param BillingIssueRequest $billingIssueRequest The request object containing details for the billing to be issued.
     * @return BillingIssueResponse An object containing the response details from the billing issue process.
     * @throws SdkException If there is an error during the billing issuance process.
     */
    public function issueBilling(Config $config, BillingIssueRequest $billingIssueRequest): BillingIssueResponse
    {
        $log = new Logger('IssueBilling');
        $log->info("IssueBilling {$config->getClientId()} {$billingIssueRequest->getYourNumber()}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BILLING);

        try {
            $jsonRequest = $billingIssueRequest->toJson();
            $jsonResponse = HttpUtils::callPost($config, $url, Constants::BILLET_BILLING_WRITE_SCOPE, "Error issuing billing", $jsonRequest);
            return BillingIssueResponse::fromJson(json_decode($jsonResponse, true));
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
     * Retrieves billing details based on the provided request code.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $requestCode The unique identifier for the billing request to be retrieved.
     * @return RetrievedBilling An object containing the details of the requested billing.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveBilling(Config $config, string $requestCode): RetrievedBilling
    {
        $log = new Logger('RetrieveIssue');
        $log->info("RetrieveIssue {$config->getClientId()} requestCode={$requestCode}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BILLING) . "/{$requestCode}";

        $jsonResponse = HttpUtils::callGet($config, $url, Constants::BILLET_BILLING_READ_SCOPE, "Error retrieving billing");

        try {
            return RetrievedBilling::fromJson(json_decode($jsonResponse, true));
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
     * Retrieves a page of billing records based on the specified parameters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int $pageSize The number of items per page.
     * @param BillingRetrievalFilter|null $filter Optional filters to be applied to the billing retrieval.
     * @param Sorting|null $sort Optional sorting criteria for the billing retrieval.
     * @return BillingPage An object containing the requested page of billing records.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveBillingPage(Config $config, string $initialDate, string $finalDate, int $page, int $pageSize, ?BillingRetrievalFilter $filter, ?Sorting $sort): BillingPage
    {
        $log = new Logger('RetrieveBillingCollection');
        $log->info("RetrieveBillingCollection {$config->getClientId()} {$initialDate}-{$finalDate}");

        return $this->getPage($config, $initialDate, $finalDate, $page, $pageSize, $filter, $sort);
    }

    /**
     * Retrieves all billing records within the specified date range, applying the given filters and sorting.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param BillingRetrievalFilter|null $filter Optional filters to be applied to the billing retrieval.
     * @param Sorting|null $sort Optional sorting criteria for the billing retrieval.
     * @return RetrievedBilling[] A list of objects containing all billing records within the specified date range.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveBillingInRange(Config $config, string $initialDate, string $finalDate, ?BillingRetrievalFilter $filter, ?Sorting $sort): array
    {
        $log = new Logger('RetrieveBillingCollection');
        $log->info("RetrieveBillingCollection {$config->getClientId()} {$initialDate}-{$finalDate}");

        $page = 0;
        $billingRecords = [];

        while (true) {
            $billingPage = $this->getPage($config, $initialDate, $finalDate, $page, null, $filter, $sort);
            $billingRecords = array_merge($billingRecords, $billingPage->getBillings());
            if ($page >= $billingPage->getTotalPages()) {
                break;
            }
            $page++;
        }

        return $billingRecords;
    }

    /**
     * Retrieves the billing PDF identified by the provided request code and saves it to a specified file.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $requestCode The unique identifier for the billing request whose PDF is to be retrieved.
     * @param string $filePath The file path where the PDF document will be saved.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveBillingInPdf(Config $config, string $requestCode, string $filePath): void
    {
        $log = new Logger('RetrieveBillingPdf');
        $log->info("RetrieveBillingPdf {$config->getClientId()} requestCode={$requestCode}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BILLING) . "/{$requestCode}/pdf";

        $jsonResponse = HttpUtils::callGet($config, $url, Constants::BILLET_BILLING_READ_SCOPE, "Error retrieving billing pdf");

        try {
            $pdfReturn = PdfReturn::fromJson(json_decode($jsonResponse, true));
            $decodedBytes = base64_decode($pdfReturn->getPdf());

            file_put_contents($filePath, $decodedBytes);
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
     * Retrieves a summary of billing records within a specified date range and optional filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param BillingRetrievalFilter|null $filter Optional filters to be applied to the billing summary retrieval.
     * @return SummaryItem[] An array of objects containing the billing summary details.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveBillingSummary(Config $config, string $initialDate, string $finalDate, ?BillingRetrievalFilter $filter): array
    {
        $log = new Logger('RetrieveBillingSummary');
        $log->info("RetrieveBillingSummary {$config->getClientId()} {$initialDate}-{$finalDate}");
        $url = UrlUtils::buildUrl($config, Constants::URL_BILLING_SUMMARY) .
            "?dataInicial=" . urlencode($initialDate) .
            "&dataFinal=" . urlencode($finalDate) .
            $this->addFilters($filter);
        $jsonResponse = HttpUtils::callGet($config, $url, Constants::BILLET_BILLING_READ_SCOPE, "Error retrieving billing summary");
        try {
            return array_map(fn($item) => SummaryItem::fromJson($item), json_decode($jsonResponse, true));
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
     * Retrieves a specific page of billing records based on the specified parameters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param BillingRetrievalFilter|null $filter Optional filters to be applied to the billing retrieval.
     * @param Sorting|null $sort Optional sorting criteria for the billing retrieval.
     * @return BillingPage An object containing the requested page of billing records.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function getPage(Config $config, string $initialDate, string $finalDate, int $page, ?int $pageSize, ?BillingRetrievalFilter $filter, ?Sorting $sort): BillingPage
    {
        $log = new Logger('RetrieveBilling');
        $url = UrlUtils::buildUrl($config, Constants::URL_BILLING) .
            "?dataInicial=" . urlencode($initialDate) .
            "&dataFinal=" . urlencode($finalDate) .
            "&paginacao.paginaAtual={$page}";
        if ($pageSize !== null) {
            $url .= "&paginacao.itensPorPagina={$pageSize}";
        }
        $url .= $this->addFilters($filter) . $this->addSort($sort);
        $jsonResponse = HttpUtils::callGet($config, $url, Constants::BILLET_BILLING_READ_SCOPE, "Error retrieving billing collection");
        try {
            return BillingPage::fromJson(json_decode($jsonResponse, true));
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
     * Constructs the query string for filters to be applied when retrieving billing records.
     *
     * @param BillingRetrievalFilter|null $filter The filter object containing filtering criteria.
     * @return string A query string that can be appended to the URL for filtering.
     */
    public function addFilters(?BillingRetrievalFilter $filter): string
    {
        if ($filter === null) {
            return "";
        }

        $stringFilter = [];
        if ($filter->getFilterDateBy() !== null) {
            $stringFilter[] = "&filtrarDataPor={$filter->getFilterDateBy()->value}";
        }
        if ($filter->getSituation() !== null) {
            $stringFilter[] = "&situacao={$filter->getSituation()->value}";
        }
        if ($filter->getPayer() !== null) {
            $stringFilter[] = "&pessoaPagadora={$filter->getPayer()}";
        }
        if ($filter->getPayerCpfCnpj() !== null) {
            $stringFilter[] = "&cpfCnpjPessoaPagadora={$filter->getPayerCpfCnpj()}";
        }
        if ($filter->getYourNumber() !== null) {
            $stringFilter[] = "&seuNumero={$filter->getYourNumber()}";
        }
        if ($filter->getBillingType() !== null) {
            $stringFilter[] = "&tipoCobranca={$filter->getBillingType()->value}";
        }

        return implode('', $stringFilter);
    }

    /**
     * Constructs the query string for sorting to be applied when retrieving billing records.
     *
     * @param Sorting|null $sort The sorting object containing sorting criteria.
     * @return string A query string that can be appended to the URL for sorting.
     */
    public function addSort(?Sorting $sort): string
    {
        if ($sort === null) {
            return "";
        }

        $order = [];
        if ($sort->getOrderBy() !== null) {
            $order[] = "&ordenarPor={$sort->getOrderBy()->value}";
        }
        if ($sort->getSortType() !== null) {
            $order[] = "&tipoOrdenacao={$sort->getSortType()->value}";
        }

        return implode('', $order);
    }
}