<?php

namespace Inter\Sdk\sdkLibrary\pix\pix;

use Exception;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Inter\Sdk\sdkLibrary\commons\utils\HttpUtils;
use Inter\Sdk\sdkLibrary\commons\utils\UrlUtils;
use Inter\Sdk\sdkLibrary\pix\models\DetailedDevolution;
use Inter\Sdk\sdkLibrary\pix\models\DevolutionRequestBody;
use Inter\Sdk\sdkLibrary\pix\models\Pix;
use Inter\Sdk\sdkLibrary\pix\models\PixPage;
use Inter\Sdk\sdkLibrary\pix\models\RetrievedPixFilter;
use Monolog\Logger;

class PixClient
{
    /**
     * Requests a devolution for a transaction identified by its end-to-end ID and the specific ID.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $e2e_id The end-to-end ID of the transaction for which the devolution is being requested.
     * @param string $id The unique identifier for the devolution request.
     * @param DevolutionRequestBody $devolution_request_body An object containing details for the devolution request.
     * @return DetailedDevolution An object containing details about the requested devolution.
     * @throws SdkException If there is an error during the request process.
     */
    public function requestDevolution(Config $config, string $e2e_id, string $id, DevolutionRequestBody $devolution_request_body): DetailedDevolution
    {
        $log = new Logger('RequestDevolution');
        $log->info("RequestDevolution {$config->getClientId()} e2eId={$e2e_id} id={$id}");
        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_PIX) . "/{$e2e_id}/devolucao/{$id}";
        try {
            $json_data = json_encode($devolution_request_body->toArray(), JSON_PRETTY_PRINT);
            $json_response = HttpUtils::callPut($config, $url, Constants::PIX_WRITE_SCOPE, "Error requesting devolution", $json_data);
            return DetailedDevolution::fromJson(json_decode($json_response, true));
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
     * Retrieves the details of a devolution based on the provided end-to-end ID and specific ID.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $e2e_id The end-to-end ID of the transaction for which the devolution details are requested.
     * @param string $id The unique identifier for the devolution to be retrieved.
     * @return DetailedDevolution An object containing the details of the retrieved devolution.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveDevolution(Config $config, string $e2e_id, string $id): DetailedDevolution
    {
        $log = new Logger('RetrieveDevolution');
        $log->info("RetrieveDevolution {$config->getClientId()} e2eId={$e2e_id} id={$id}");
        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_PIX) . "/{$e2e_id}/devolucao/{$id}";
        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_READ_SCOPE, "Error retrieving devolution");
        try {
            return DetailedDevolution::fromJson(json_decode($json_response, true));
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
     * Retrieves the details of a Pix transaction based on the provided end-to-end ID.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $e2e_id The end-to-end ID of the Pix transaction to be retrieved.
     * @return Pix An object containing the details of the retrieved Pix transaction.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrievePixTransaction(Config $config, string $e2e_id): Pix
    {
        $log = new Logger('RetrievePix');
        $log->info("RetrievePix {$config->getClientId()} e2eId={$e2e_id}");
        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_PIX) . "/{$e2e_id}";
        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_READ_SCOPE, "Error retrieving pix");
        try {
            return Pix::fromJson(json_decode($json_response, true));
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
     * Retrieves a paginated list of Pix transactions based on the specified date range, page number, and filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int $pageSize The number of items per page.
     * @param RetrievedPixFilter $filter An object containing filter criteria.
     * @return PixPage An object containing the requested page of Pix transactions.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrievePixPage(Config $config, string $initialDate, string $finalDate, int $page, int $pageSize, RetrievedPixFilter $filter): PixPage
    {
        $log = new Logger('RetrievePixList');
        $log->info("RetrievePixList {$config->getClientId()} {$initialDate} - {$finalDate} page={$page}");

        return $this->getPage($config, $initialDate, $finalDate, $page, $pageSize, $filter);
    }

    /**
     * Retrieves all Pix transactions within the specified date range and filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param RetrievedPixFilter $filter An object containing filter criteria.
     * @return Pix[] A list of objects containing all retrieved Pix transactions.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrievePixListInRange(Config $config, string $initialDate, string $finalDate, RetrievedPixFilter $filter): array
    {
        $log = new Logger('RetrievePixList');
        $log->info("RetrievePixList {$config->getClientId()} {$initialDate} - {$finalDate}");

        $page = 0;
        $pix_list = [];

        while (true) {
            $pix_page = $this->getPage($config, $initialDate, $finalDate, $page, (int)null, $filter);
            $pix_list = array_merge($pix_list, $pix_page->getPixList());
            $page++;

            if ($page >= $pix_page->getTotalPages()) {
                break;
            }
        }

        return $pix_list;
    }

    /**
     * Retrieves a specific page of Pix transactions based on the provided criteria.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int $pageSize The number of items per page.
     * @param RetrievedPixFilter|null $filter An object containing filter criteria.
     * @return PixPage An object containing the requested page of Pix transactions.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function getPage(Config $config, string $initialDate, string $finalDate, int $page, int $pageSize, ?RetrievedPixFilter $filter): PixPage
    {
        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_PIX) .
            "?inicio=" . urlencode($initialDate) .
            "&fim=" . urlencode($finalDate) .
            "&paginacao.paginaAtual={$page}";

        if ($pageSize !== 0) {
            $url .= "&paginacao.itensPorPagina={$pageSize}";
        }

        if ($filter !== null) {
            $url .= $this->addFilters($filter);
        }

        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_READ_SCOPE, "Error retrieving pix");

        try {
            return PixPage::fromJson(json_decode($json_response, true));
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
     * @param RetrievedPixFilter $filter An object containing filter criteria.
     * @return string A string containing the appended filter parameters for the URL.
     */
    public function addFilters(RetrievedPixFilter $filter): string
    {
        if ($filter === null) {
            return "";
        }

        $string_filter = [];

        if ($filter->getTxId() !== null) {
            $string_filter[] = "&txId={$filter->getTxId()}";
        }
        if ($filter->getTxIdPresent() !== null) {
            $string_filter[] = "&txIdPresente={$filter->getTxIdPresent()}";
        }
        if ($filter->getDevolutionPresent() !== null) {
            $string_filter[] = "&devolucaoPresente={$filter->getDevolutionPresent()}";
        }
        if ($filter->getCpf() !== null) {
            $string_filter[] = "&cpf={$filter->getCpf()}";
        }
        if ($filter->getCnpj() !== null) {
            $string_filter[] = "&cnpj={$filter->getCnpj()}";
        }

        return implode('', $string_filter);
    }

}