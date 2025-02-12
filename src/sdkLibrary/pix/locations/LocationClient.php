<?php

namespace Inter\Sdk\sdkLibrary\pix\locations;

use Exception;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Inter\Sdk\sdkLibrary\commons\utils\HttpUtils;
use Inter\Sdk\sdkLibrary\commons\utils\UrlUtils;
use Inter\Sdk\sdkLibrary\pix\enums\ImmediateBillingType;
use Inter\Sdk\sdkLibrary\pix\models\Location;
use Inter\Sdk\sdkLibrary\pix\models\LocationPage;
use Inter\Sdk\sdkLibrary\pix\models\RetrieveLocationFilter;
use Monolog\Logger;

class LocationClient
{
    /**
     * Includes a new location based on the provided configuration and immediate billing type.
     *
     * @param Config $config The configuration object containing client information.
     * @param ImmediateBillingType $immediate_billing_type The type of immediate billing.
     * @return Location An object containing the details of the included location.
     * @throws SdkException If there is an error during the inclusion process.
     */
    public function includeLocation(Config $config, ImmediateBillingType $immediate_billing_type): Location
    {
        $log = new Logger('IncludeLocation');
        $log->info("IncludeLocation pix {$config->getClientId()} {$immediate_billing_type->value}");
        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_LOCATIONS);
        $request = ["tipoCob" => $immediate_billing_type->name];
        try {
            $json_data = json_encode($request, JSON_PRETTY_PRINT);
            $json_response = HttpUtils::callPost($config, $url, Constants::PIX_LOCATION_WRITE_SCOPE, "Error including location", $json_data);
            return Location::fromJson(json_decode($json_response, true));
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
     * Retrieves the details of a location based on the provided configuration and location ID.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $id The unique identifier for the location to be retrieved.
     * @return Location An object containing the details of the retrieved location.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveLocation(Config $config, string $id): Location
    {
        $log = new Logger('RetrieveLocation');
        $log->info("RetrieveLocation {$config->getClientId()} id={$id}");
        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_LOCATIONS) . "/{$id}";
        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_LOCATION_READ_SCOPE, "Error retrieving location");
        try {
            return Location::fromJson(json_decode($json_response, true));
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
     * Retrieves a paginated list of locations based on the specified date range, page number, and filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param RetrieveLocationFilter $filter An object containing filter criteria.
     * @return LocationPage An object containing the requested page of locations.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveLocationPage(Config $config, string $initialDate, string $finalDate, int $page, ?int $pageSize, RetrieveLocationFilter $filter): LocationPage
    {
        $log = new Logger('RetrieveLocationsList');
        $log->info("RetrieveLocationsList {$config->getClientId()} {$initialDate} - {$finalDate} pagina={$page}");
        return $this->getPage($config, $initialDate, $finalDate, $page, $pageSize, $filter);
    }

    /**
     * Retrieves all locations within the specified date range and filters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param RetrieveLocationFilter $filter An object containing filter criteria.
     * @return Location[] A list of objects containing all retrieved locations.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveLocationInRange(Config $config, string $initialDate, string $finalDate, RetrieveLocationFilter $filter): array
    {
        $log = new Logger('RetrieveLocationsList');
        $log->info("RetrieveLocationsList {$config->getClientId()} {$initialDate} - {$finalDate}");

        $page = 0;
        $locs = [];

        while (true) {
            $location_page = $this->getPage($config, $initialDate, $finalDate, $page, null, $filter);
            $locs = array_merge($locs, $location_page->getLocations());
            $page++;
            if ($page >= $location_page->getTotalPages()) {
                break;
            }
        }

        return $locs;
    }

    /**
     * Unlinks a location based on the provided configuration and location ID.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $id The unique identifier for the location to be unlinked.
     * @return Location An object confirming the unlinking of the location.
     * @throws SdkException If there is an error during the unlinking process.
     */
    public function unlinkLocation(Config $config, string $id): Location
    {
        $log = new Logger('UnlinkLocation');
        $log->info("UnlinkLocation {$config->getClientId()} id={$id}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_LOCATIONS) . "/{$id}/txid";
        $json_response = HttpUtils::callDelete($config, $url, Constants::PIX_LOCATION_WRITE_SCOPE, "Error unlinking location");

        try {
            return Location::fromJson(json_decode($json_response, true));
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
     * Retrieves a specific page of locations based on the provided criteria.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param RetrieveLocationFilter $filter An object containing filter criteria.
     * @return LocationPage An object containing the requested page of locations.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function getPage(Config $config, string $initialDate, string $finalDate, int $page, ?int $pageSize, RetrieveLocationFilter $filter): LocationPage
    {
        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_LOCATIONS) .
            "?inicio=" . urlencode($initialDate) .
            "&fim=" . urlencode($finalDate) .
            "&paginacao.paginaAtual={$page}";

        if ($pageSize !== null) {
            $url .= "&paginacao.itensPorPagina={$pageSize}";
        }

        if ($filter !== null) {
            $url .= $this->addFilters($filter);
        }

        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_LOCATION_READ_SCOPE, "Error retrieving locations");

        try {
            return LocationPage::fromJson(json_decode($json_response, true));
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
     * @param RetrieveLocationFilter $filter An object containing filter criteria.
     * @return string A string containing the appended filter parameters for the URL.
     */
    public function addFilters(RetrieveLocationFilter $filter): string
    {
        if ($filter === null) {
            return "";
        }

        $string_filter = [];

        if ($filter->getTxIdPresent() !== null) {
            $string_filter[] = "&txIdPresente={$filter->getTxIdPresent()}";
        }
        if ($filter->getBillingType() !== null) {
            $string_filter[] = "&tipoCob={$filter->getBillingType()->value}";
        }

        return implode('', $string_filter);
    }
}