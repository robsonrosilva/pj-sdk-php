<?php

namespace Inter\Sdk\sdkLibrary\banking\bankstatement;

use Exception;
use Inter\Sdk\sdkLibrary\banking\models\BankStatement;
use Inter\Sdk\sdkLibrary\banking\models\EnrichedBankStatementPage;
use Inter\Sdk\sdkLibrary\banking\models\FilterRetrieveEnrichedStatement;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\commons\models\Config;
use Inter\Sdk\sdkLibrary\commons\models\Error;
use Inter\Sdk\sdkLibrary\commons\models\PdfReturn;
use Inter\Sdk\sdkLibrary\commons\structure\Constants;
use Inter\Sdk\sdkLibrary\commons\utils\HttpUtils;
use Inter\Sdk\sdkLibrary\commons\utils\UrlUtils;
use Monolog\Logger;

class BankStatementClient
{
    /**
     * Retrieves the bank statement for a specified date range.
     *
     * @param Config $config The configuration object containing necessary parameters, such as client ID.
     * @param string $initialDate The start date for retrieving the bank statement in the appropriate format.
     * @param string $finalDate The end date for retrieving the bank statement in the appropriate format.
     * @return BankStatement An instance of BankStatement containing the retrieved statement information.
     * @throws SdkException If there is an error during the retrieval process or if the response format is incorrect.
     */
    public function retrieveStatement(Config $config, string $initialDate, string $finalDate): BankStatement
    {
        $log = new Logger('BankStatementRetrieval');
        $log->info("RetrieveBankStatement {$config->getClientId()} {$initialDate}-{$finalDate}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_STATEMENT);
        $url .= "?dataInicio=" . urlencode($initialDate) . "&dataFim=" . urlencode($finalDate);

        $jsonResponse = HttpUtils::callGet($config, $url, Constants::READ_BALANCE_SCOPE, "Error retrieving statement");

        try {
            return BankStatement::fromJson(json_decode($jsonResponse, true));
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
     * Retrieves the bank statement in PDF format for a specified date range and saves it to a file.
     *
     * @param Config $config The configuration object containing necessary parameters such as client ID.
     * @param string $initialDate The start date for the bank statement period.
     * @param string $finalDate The end date for the bank statement period.
     * @param string $file The path where the PDF file will be saved.
     * @throws SdkException If an error occurs during the retrieval of the statement or if an error
     *                      occurs during the PDF decoding or file writing process.
     */
    public function retrieveStatementInPdf(Config $config, string $initialDate, string $finalDate, string $file): void
    {
        $log = new Logger('BankStatementPdfRetrieval');
        $log->info("RetrieveBankStatementInPdf {$config->getClientId()} {$initialDate}-{$finalDate}");

        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_STATEMENT_PDF);
        $url .= "?dataInicio=" . urlencode($initialDate) . "&dataFim=" . urlencode($finalDate);

        $jsonResponse = HttpUtils::callGet($config, $url, Constants::READ_BALANCE_SCOPE, "Error retrieving statement in pdf");

        try {
            $pdfReturn = PdfReturn::fromJson(json_decode($jsonResponse, true));
            $decodedBytes = base64_decode($pdfReturn->getPdf() ?? '');
            file_put_contents($file, $decodedBytes);
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
     * Retrieves a specific page of enriched bank statements within a given date range.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date of the statement range (inclusive).
     * @param string $finalDate The end date of the statement range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param FilterRetrieveEnrichedStatement|null $filterRetrieve Optional filters for retrieving enriched bank statements.
     * @return EnrichedBankStatementPage An instance containing the requested page of enriched statements.
     * @throws SdkException
     */
    public function retrieveStatementPage(Config $config, string $initialDate, string $finalDate, int $page, ?int $pageSize, ?FilterRetrieveEnrichedStatement $filterRetrieve): EnrichedBankStatementPage
    {
        $log = new Logger('EnrichedBankStatementRetrieval');
        $log->info("RetrieveEnrichedBankStatement {$config->getClientId()} {$initialDate}-{$finalDate}");

        return $this->getPage($config, $initialDate, $finalDate, $page, $pageSize, $filterRetrieve);
    }

    /**
     * Retrieves a list of enriched transactions within a given date range.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date of the statement range (inclusive).
     * @param string $finalDate The end date of the statement range (inclusive).
     * @param FilterRetrieveEnrichedStatement|null $filterRetrieve Optional filters for retrieving enriched bank statements.
     * @return array A list of all transactions within the date range.
     * @throws SdkException
     */
    public function retrieveStatementWithRange(Config $config, string $initialDate, string $finalDate, ?FilterRetrieveEnrichedStatement $filterRetrieve): array
    {
        $log = new Logger('EnrichedBankStatementRetrieval');
        $log->info("RetrieveEnrichedBankStatement {$config->getClientId()} {$initialDate}-{$finalDate}");

        $page = 0;
        $transactions = [];

        while (true) {
            $transactionPage = $this->getPage($config, $initialDate, $finalDate, $page, null, $filterRetrieve);
            $transactions = array_merge($transactions, $transactionPage->getTransactions());

            if ($page >= $transactionPage->getTotalPages()) {
                break;
            }
            $page++;
        }

        return $transactions;
    }

    /**
     * Retrieves a page of enriched bank statements based on the provided parameters.
     *
     * @param Config $config The configuration object containing client information.
     * @param string $initialDate The start date of the statement range (inclusive).
     * @param string $finalDate The end date of the statement range (inclusive).
     * @param int $page The page number to retrieve.
     * @param int|null $pageSize The number of items per page (optional).
     * @param FilterRetrieveEnrichedStatement|null $filterRetrieve Optional filters for retrieving enriched bank statements.
     * @return EnrichedBankStatementPage An instance containing the requested page of enriched statements.
     * @throws SdkException If there is an error during the retrieval process.
     */
    public function getPage(Config $config, string $initialDate, string $finalDate, int $page, ?int $pageSize, ?FilterRetrieveEnrichedStatement $filterRetrieve): EnrichedBankStatementPage
    {
        $url = UrlUtils::buildUrl($config, Constants::URL_BANKING_ENRICHED_STATEMENT);
        $url .= "?dataInicio=" . urlencode($initialDate) . "&dataFim=" . urlencode($finalDate) . "&pagina={$page}";

        if ($pageSize !== null) {
            $url .= "&tamanhoPagina={$pageSize}";
        }

        $url .= $this->addFilters($filterRetrieve);

        $jsonResponse = HttpUtils::callGet($config, $url, Constants::READ_BALANCE_SCOPE, "Error retrieving enriched statement");

        try {
            return EnrichedBankStatementPage::fromJson(json_decode($jsonResponse, true));
        } catch (Exception $ioException) {
            $log = new Logger('EnrichedBankStatementPageRetrieval');
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
     * Constructs the query string for filters to be applied when retrieving enriched bank statements.
     *
     * @param FilterRetrieveEnrichedStatement|null $filterRetrieve The filter object containing filtering criteria.
     * @return string A query string that can be appended to the URL for filtering.
     */
    public function addFilters(?FilterRetrieveEnrichedStatement $filterRetrieve): string
    {
        if ($filterRetrieve === null) {
            return "";
        }

        $stringFilter = [];

        if ($filterRetrieve->getOperationType() !== null) {
            $stringFilter[] = "&tipoOperacao={$filterRetrieve->getOperationType()}";
        }

        if ($filterRetrieve->getTransactionType() !== null) {
            $stringFilter[] = "&tipoTransacao={$filterRetrieve->getTransactionType()}";
        }

        return implode('', $stringFilter);
    }
}
