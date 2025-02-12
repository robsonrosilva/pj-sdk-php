<?php

namespace Inter\Sdk\sdkLibrary\pix\duebilling;

use Exception;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Inter\Sdk\sdkLibrary\commons\utils\HttpUtils;
use Inter\Sdk\sdkLibrary\commons\utils\UrlUtils;
use Inter\Sdk\sdkLibrary\pix\models\DetailedDuePixBilling;
use Inter\Sdk\sdkLibrary\pix\models\DueBilling;
use Inter\Sdk\sdkLibrary\pix\models\DueBillingPage;
use Inter\Sdk\sdkLibrary\pix\models\GeneratedDueBilling;
use Inter\Sdk\sdkLibrary\pix\models\RetrieveDueBillingFilter;
use Monolog\Logger;

class DueBillingClient
{
    /**
     * Includes a due billing entry into the system for a specified transaction ID.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $txid The transaction ID associated with the due billing.
     * @param DueBilling $billing The object containing the billing details to be included.
     * @return GeneratedDueBilling An object containing the details of the generated billing.
     * @throws SdkException If there is an error during the inclusion process.
     */
    public function includeDueBilling(Config $config, string $txid, DueBilling $billing): GeneratedDueBilling
    {
        $log = new Logger('IncludeDueBilling');
        $log->info("IncludeDueBilling {$config->getClientId()} {$txid}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_SCHEDULED_BILLINGS) . "/{$txid}";

        try {
            $json_data = $billing->toJson();
            $json_response = HttpUtils::callPut($config, $url, Constants::PIX_SCHEDULED_BILLING_WRITE_SCOPE, "Error including due billing", $json_data);
            return GeneratedDueBilling::fromJson(json_decode($json_response, true));
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
     * Retrieves detailed information about a scheduled Pix billing using the provided transaction ID.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $txid The transaction ID associated with the scheduled Pix billing.
     * @return DetailedDuePixBilling An object containing the details of the scheduled billing.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveDueBilling(Config $config, string $txid): DetailedDuePixBilling
    {
        $log = new Logger('RetrieveDueBilling');
        $log->info("RetrieveDueBilling {$config->getClientId()} txId={$txid}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_SCHEDULED_BILLINGS) . "/{$txid}";

        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_SCHEDULED_BILLING_READ_SCOPE, "Error retrieving due billing");

        try {
            return DetailedDuePixBilling::fromJson(json_decode($json_response, true));
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
     * Retrieves a page of scheduled Pix billings within a specified date range and optional filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param RetrieveDueBillingFilter|null $filter Optional filters to be applied during retrieval.
     * @return DueBillingPage An object containing the requested page of due billings.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveDueBillingPage(Config $config, string $initialDate, string $finalDate, int $page, ?int $pageSize, ?RetrieveDueBillingFilter $filter): DueBillingPage
    {
        $log = new Logger('RetrieveDueBillingList');
        $log->info("RetrieveDueBillingList {$config->getClientId()} {$initialDate} - {$finalDate} page={$page}");

        return $this->getPage($config, $initialDate, $finalDate, $page, $pageSize, $filter);
    }

    /**
     * Retrieves all scheduled Pix billings within a specified date range and applies the given filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param RetrieveDueBillingFilter|null $filter Optional filters to be applied during retrieval.
     * @return DetailedDuePixBilling[] A list of objects containing all retrieved billings.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveDueBillingsInRange(Config $config, string $initialDate, string $finalDate, ?RetrieveDueBillingFilter $filter): array
    {
        $log = new Logger('RetrieveDueBillingList');
        $log->info("RetrieveDueBillingList {$config->getClientId()} {$initialDate} - {$finalDate}");

        $page = 0;
        $billings = [];

        while (true) {
            $dueBillingPage = $this->getPage($config, $initialDate, $finalDate, $page, null, $filter);
            $billings = array_merge($billings, $dueBillingPage->getDueBillings());
            $page++;

            if ($page >= $dueBillingPage->getTotalPages()) {
                break;
            }
        }

        return $billings;
    }

    /**
     * Reviews a scheduled Pix billing entry based on the specified transaction ID.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $txid The transaction ID associated with the due billing to be reviewed.
     * @param DueBilling $billing The object containing the updated billing details.
     * @return GeneratedDueBilling An object containing the details of the reviewed billing.
     * @throws SdkException If there is an error during the review process.
     */
    public function reviewDueBilling(Config $config, string $txid, DueBilling $billing): GeneratedDueBilling
    {
        $log = new Logger('ReviewDueBilling');
        $log->info("ReviewDueBilling {$config->getClientId()} {$txid}");
        try {
            $url = UrlUtils::buildUrl($config, Constants::URL_PIX_SCHEDULED_BILLINGS) . "/{$txid}";
            $json_data = $billing->toJson();
            $json_response = HttpUtils::callPatch($config, $url, Constants::PIX_SCHEDULED_BILLING_WRITE_SCOPE, "Error retrieving due billing", $json_data);
            return GeneratedDueBilling::fromJson(json_decode($json_response, true));
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
     * Retrieves a specific page of scheduled Pix billings based on the provided criteria.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param RetrieveDueBillingFilter|null $filter Optional filters to be applied during retrieval.
     * @return DueBillingPage An object containing the requested page of due billings.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function getPage(Config $config, string $initialDate, string $finalDate, int $page, ?int $pageSize, ?RetrieveDueBillingFilter $filter): DueBillingPage
    {
        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_SCHEDULED_BILLINGS) .
            "?inicio=" . urlencode($initialDate) .
            "&fim=" . urlencode($finalDate) .
            "&paginacao.paginaAtual={$page}";
        if ($pageSize !== null) {
            $url .= "&paginacao.itensPorPagina={$pageSize}";
        }
        $url .= $this->addFilters($filter);
        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_SCHEDULED_BILLING_READ_SCOPE, "Error retrieving due billing");
        try {
            return DueBillingPage::fromJson(json_decode($json_response, true));
        } catch (Exception $ioException) {
            $log = new Logger('GetPage');
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
     * Constructs the query string for filters to be applied when retrieving due billings.
     *
     * @param RetrieveDueBillingFilter|null $filter The filter object containing filtering criteria.
     * @return string A query string that can be appended to the URL for filtering.
     */
    public function addFilters(?RetrieveDueBillingFilter $filter): string
    {
        if ($filter === null) {
            return "";
        }
        $string_filter = [];
        if ($filter->getCpf() !== null) {
            $string_filter[] = "&cpf={$filter->getCpf()}";
        }
        if ($filter->getCnpj() !== null) {
            $string_filter[] = "&cnpj={$filter->getCnpj()}";
        }
        if ($filter->getLocationPresent() !== null) {
            $string_filter[] = "&locationPresente={$filter->getLocationPresent()}";
        }
        if ($filter->getStatus() !== null) {
            $string_filter[] = "&status={$filter->getStatus()->value}";
        }
        return implode('', $string_filter);
    }
}