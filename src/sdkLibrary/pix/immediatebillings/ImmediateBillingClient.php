<?php

namespace Inter\Sdk\sdkLibrary\pix\immediatebillings;

use Exception;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Inter\Sdk\sdkLibrary\commons\utils\HttpUtils;
use Inter\Sdk\sdkLibrary\commons\utils\UrlUtils;
use Inter\Sdk\sdkLibrary\pix\models\BillingPage;
use Inter\Sdk\sdkLibrary\pix\models\DetailedImmediatePixBilling;
use Inter\Sdk\sdkLibrary\pix\models\GeneratedImmediateBilling;
use Inter\Sdk\sdkLibrary\pix\models\PixBilling;
use Inter\Sdk\sdkLibrary\pix\models\RetrieveImmediateBillingsFilter;
use Monolog\Logger;

class ImmediateBillingClient
{
    /**
     * Includes a new immediate billing or updates an existing one based on the provided configuration and billing details.
     *
     * @param Config $config The configuration object containing client information.
     * @param PixBilling $billing The object containing the details of the billing to be included.
     * @return GeneratedImmediateBilling An object containing the details of the generated immediate billing.
     * @throws SdkException|\DateMalformedStringException If there is an error during the inclusion process.
     */
    public function includeImmediateBilling(Config $config, PixBilling $billing): GeneratedImmediateBilling
    {
        $log = new Logger('IncludeImmediateBilling');
        $log->info("IncludeImmediateBilling {$config->getClientId()} {$billing->getTxid()}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_IMMEDIATE_BILLINGS);

        try {
            $json_data = $billing->toJson();
            if ($billing->getTxid() === null) {
                $json_response = HttpUtils::callPost($config, $url, Constants::PIX_IMMEDIATE_BILLING_WRITE_SCOPE, "Error including immediate billing", $json_data);
            } else {
                $url .= "/{$billing->getTxid()}";
                $json_response = HttpUtils::callPut($config, $url, Constants::PIX_IMMEDIATE_BILLING_WRITE_SCOPE, "Error including immediate billing", $json_data);
            }

            return GeneratedImmediateBilling::fromJson(json_decode($json_response, true));
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
     * Retrieves the details of an immediate billing based on the provided configuration and transaction ID.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $tx_id The unique transaction ID for the immediate billing to be retrieved.
     * @return DetailedImmediatePixBilling An object containing the details of the retrieved immediate billing.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveImmediateBilling(Config $config, string $tx_id): DetailedImmediatePixBilling
    {
        $log = new Logger('RetrieveImmediateBilling');
        $log->info("RetrieveImmediateBilling {$config->getClientId()} txId={$tx_id}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_IMMEDIATE_BILLINGS) . "/{$tx_id}";

        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_IMMEDIATE_BILLING_READ_SCOPE, "Error retrieving immediate billing");

        try {
            return DetailedImmediatePixBilling::fromJson(json_decode($json_response, true));
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
     * Retrieves a paginated list of immediate billings based on the specified date range, page number, and filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param RetrieveImmediateBillingsFilter $filter An object containing filter criteria.
     * @return BillingPage An object containing the requested page of immediate billings.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveImmediateBillingPage(Config $config, string $initialDate, string $finalDate, int $page, ?int $pageSize, RetrieveImmediateBillingsFilter $filter): BillingPage
    {
        $log = new Logger('RetrieveImmediateBillingList');
        $log->info("RetrieveImmediateBillingList {$config->getClientId()} {$initialDate} - {$finalDate} page={$page}");
        return $this->getPage($config, $initialDate, $finalDate, $page, $pageSize, $filter);
    }
    /**
     * Retrieves all immediate billings within the specified date range and filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param RetrieveImmediateBillingsFilter $filter An object containing filter criteria.
     * @return DetailedImmediatePixBilling[] A list of objects containing all retrieved immediate billings.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveImmediateBillingsInRange(Config $config, string $initialDate, string $finalDate, RetrieveImmediateBillingsFilter $filter): array
    {
        $log = new Logger('RetrieveImmediateBillingList');
        $log->info("RetrieveImmediateBillingList {$config->getClientId()} {$initialDate} - {$finalDate}");
        $page = 0;
        $billings = [];
        while (true) {
            $billingPage = $this->getPage($config, $initialDate, $finalDate, $page, null, $filter);
            $billings = array_merge($billings, $billingPage->getBillings());
            $page++;
            if ($page >= $billingPage->getTotalPages()) {
                break;
            }
        }
        return $billings;
    }
    /**
     * Reviews an immediate billing based on the provided configuration and billing details.
     *
     * @param Config $config The configuration object containing client information.
     * @param PixBilling $cobranca The object containing the details of the billing to be reviewed.
     * @return GeneratedImmediateBilling An object containing the details of the reviewed immediate billing.
     * @throws SdkException If there is an error during the review process.
     */
    public function reviewImmediateBilling(Config $config, PixBilling $cobranca): GeneratedImmediateBilling
    {
        $log = new Logger('ReviewImmediateBilling');
        $log->info("ReviewImmediateBilling {$config->getClientId()} {$cobranca->getTxid()}");
        try {
            $url = UrlUtils::buildUrl($config, Constants::URL_PIX_IMMEDIATE_BILLINGS) . "/{$cobranca->getTxid()}";
            $json_data = $cobranca->toJson();
            $json_response = HttpUtils::callPatch($config, $url, Constants::PIX_IMMEDIATE_BILLING_WRITE_SCOPE, "Error reviewing immediate billing", $json_data);
            return GeneratedImmediateBilling::fromJson(json_decode($json_response, true));
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
     * Retrieves a specific page of immediate billings based on the provided criteria.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param RetrieveImmediateBillingsFilter $filter An object containing filter criteria.
     * @return BillingPage An object containing the requested page of immediate billings.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function getPage(Config $config, string $initialDate, string $finalDate, int $page, ?int $pageSize, RetrieveImmediateBillingsFilter $filter): BillingPage
    {
        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_IMMEDIATE_BILLINGS) .
            "?inicio=" . urlencode($initialDate) .
            "&fim=" . urlencode($finalDate) .
            "&paginacao.paginaAtual={$page}";

        if ($pageSize !== null) {
            $url .= "&paginacao.itensPorPagina={$pageSize}";
        }

        if ($filter !== null) {
            $url .= $this->addFilters($filter);
        }

        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_IMMEDIATE_BILLING_READ_SCOPE, "Error retrieving list of immediate billings");

        try {
            return BillingPage::fromJson(json_decode($json_response, true));
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
     * Adds filter parameters to the URL based on the provided filter criteria.
     *
     * @param RetrieveImmediateBillingsFilter $filter An object containing filter criteria.
     * @return string A string containing the appended filter parameters for the URL.
     */
    public function addFilters(RetrieveImmediateBillingsFilter $filter): string
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