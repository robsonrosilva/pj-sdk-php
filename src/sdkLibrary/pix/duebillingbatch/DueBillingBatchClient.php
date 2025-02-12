<?php

namespace Inter\Sdk\sdkLibrary\pix\duebillingbatch;

use Exception;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Inter\Sdk\sdkLibrary\commons\utils\HttpUtils;
use Inter\Sdk\sdkLibrary\commons\utils\UrlUtils;
use Inter\Sdk\sdkLibrary\pix\models\DueBillingBatch;
use Inter\Sdk\sdkLibrary\pix\models\DueBillingBatchPage;
use Inter\Sdk\sdkLibrary\pix\models\DueBillingBatchSummary;
use Inter\Sdk\sdkLibrary\pix\models\IncludeDueBillingBatchRequest;
use Monolog\Logger;

class DueBillingBatchClient
{
    /**
     * Includes a batch request for due billing based on the provided configuration,
     * batch ID, and request details.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $batch_id The unique identifier for the batch of due billings to be included.
     * @param IncludeDueBillingBatchRequest $request The object containing the details of the due billing batch request to be included.
     * @throws SdkException If there is an error during the inclusion process.
     */
    public function includeDueBillingBatch(Config $config, string $batch_id, IncludeDueBillingBatchRequest $request): void
    {
        $log = new Logger('IncludeDueBillingBatch');
        $log->info("IncludeDueBillingBatch {$config->getClientId()} {$request->toJson()}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_SCHEDULED_BILLINGS_BATCH) . "/{$batch_id}";

        try {
            $json_data = $request->toJson();
            HttpUtils::callPut($config, $url, Constants::PIX_SCHEDULED_BILLING_BATCH_WRITE_SCOPE, "Error including due billing in batch", $json_data);
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
     * Retrieves a due billing batch based on the provided configuration and batch ID.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $batch_id The unique identifier for the due billing batch to be retrieved.
     * @return DueBillingBatch An object containing the details of the retrieved due billing batch.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveDueBillingBatch(Config $config, string $batch_id): DueBillingBatch
    {
        $log = new Logger('RetrieveDueBillingBatch');
        $log->info("RetrieveDueBillingBatch {$config->getClientId()} id={$batch_id}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_SCHEDULED_BILLINGS_BATCH) . "/{$batch_id}";

        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_SCHEDULED_BILLING_BATCH_READ_SCOPE, "Error retrieving due billing batch");

        try {
            return DueBillingBatch::fromJson(json_decode($json_response, true));
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
     * Retrieves a paginated list of due billing batches based on the specified date range and page number.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @return DueBillingBatchPage An object containing the requested page of due billing batches.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveDueBillingBatchPage(Config $config, string $initialDate, string $finalDate, int $page, ?int $pageSize): DueBillingBatchPage
    {
        $log = new Logger('RetrieveDueBillingBatchList');
        $log->info("RetrieveDueBillingBatchList {$config->getClientId()} {$initialDate} - {$finalDate} page={$page}");

        return $this->getPage($config, $initialDate, $finalDate, $page, $pageSize);
    }

    /**
     * Retrieves all due billing batches within the specified date range.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @return DueBillingBatch[] A list of objects containing all retrieved billing batches.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveDueBillingBatchesInRange(Config $config, string $initialDate, string $finalDate): array
    {
        $log = new Logger('RetrieveDueBillingBatchList');
        $log->info("RetrieveDueBillingBatchList {$config->getClientId()} {$initialDate} - {$finalDate}");

        $page = 0;
        $batches = [];

        while (true) {
            $dueBillingPage = $this->getPage($config, $initialDate, $finalDate, $page, null);
            $batches = array_merge($batches, $dueBillingPage->getBatches());
            $page++;

            if ($page >= $dueBillingPage->getTotalPages()) {
                break;
            }
        }

        return $batches;
    }

    /**
     * Reviews a due billing batch based on the provided configuration, batch ID, and review request details.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $batch_id The unique identifier for the due billing batch to be reviewed.
     * @param IncludeDueBillingBatchRequest $request The object containing the details to update the review.
     * @throws SdkException If there is an error during the review process.
     */
    public function reviewDueBillingBatch(Config $config, string $batch_id, IncludeDueBillingBatchRequest $request): void
    {
        $log = new Logger('IncludeDueBillingBatch');
        $log->info("IncludeDueBillingBatch {$config->getClientId()} {$request->toJson()}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_SCHEDULED_BILLINGS_BATCH) . "/{$batch_id}";

        try {
            $json_data = $request->toJson();
            HttpUtils::callPatch($config, $url, Constants::PIX_SCHEDULED_BILLING_BATCH_WRITE_SCOPE, "Error reviewing due billing in batch", $json_data);
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
     * Retrieves the summary of a due billing batch based on the provided configuration and batch ID.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $batch_id The unique identifier for the due billing batch to be retrieved.
     * @return DueBillingBatchSummary An object containing the summary details of the retrieved due billing batch.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function retrieveDueBillingBatchSummary(Config $config, string $batch_id): DueBillingBatchSummary
    {
        $log = new Logger('RetrieveDueBillingBatch');
        $log->info("RetrieveDueBillingBatch {$config->getClientId()} id={$batch_id}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_SCHEDULED_BILLINGS_BATCH) . "/{$batch_id}/sumario";

        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_SCHEDULED_BILLING_BATCH_READ_SCOPE, "Error retrieving due billing batch summary");

        try {
            return DueBillingBatchSummary::fromJson(json_decode($json_response, true));
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
     * Retrieves a due billing batch identified by the specified ID and situation.
     *
     * @param Config $config The configuration object containing client information for making the request.
     * @param string $batch_id The unique identifier of the due billing batch to retrieve.
     * @param string $situation The situation status to filter the due billing batch.
     * @return DueBillingBatch An object representing the retrieved billing batch.
     * @throws SdkException If an error occurs while making the HTTP request or processing the response.
     */
    public function retrieveDueBillingBatchBySituation(Config $config, string $batch_id, string $situation): DueBillingBatch
    {
        $log = new Logger('RetrieveDueBillingBatchSituation');
        $log->info("RetrieveDueBillingBatchSituation {$config->getClientId()} id={$batch_id}");

        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_SCHEDULED_BILLINGS_BATCH) . "/{$batch_id}/situacao/{$situation}";

        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_SCHEDULED_BILLING_BATCH_READ_SCOPE, "Error retrieving due billing batch by situation");

        try {
            return DueBillingBatch::fromJson(json_decode($json_response, true));
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
     * Retrieves a specific page of due billing batches based on the provided criteria.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date for the retrieval range (inclusive).
     * @param string $finalDate The end date for the retrieval range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @return DueBillingBatchPage An object containing the requested page of due billing batches.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function getPage(Config $config, string $initialDate, string $finalDate, int $page, ?int $pageSize): DueBillingBatchPage
    {
        $url = UrlUtils::buildUrl($config, Constants::URL_PIX_SCHEDULED_BILLINGS_BATCH) . "?inicio=" . urlencode($initialDate) . "&fim=" . urlencode($finalDate) . "&paginacao.paginaAtual={$page}";

        if ($pageSize !== null) {
            $url .= "&paginacao.itensPorPagina={$pageSize}";
        }

        $json_response = HttpUtils::callGet($config, $url, Constants::PIX_SCHEDULED_BILLING_BATCH_READ_SCOPE, "Error retrieving due billing batch");

        try {
            return DueBillingBatchPage::fromJson(json_decode($json_response, true));
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
}