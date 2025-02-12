<?php

namespace Inter\Sdk\functionalTests\menus;

use Inter\Sdk\functionalTests\BankingFunctionalTests;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use JsonException;
use ValueError;

class BankingMenu
{
    const RETRIEVE_STATEMENT = 1;
    const RETRIEVE_STATEMENT_PDF = 2;
    const RETRIEVE_ENRICHED_STATEMENT = 3;
    const RETRIEVE_ENRICHED_STATEMENT_PAGINATION = 4;
    const RETRIEVE_BALANCE = 5;
    const INCLUDE_PAYMENT = 6;
    const SEARCH_PAYMENTS = 7;
    const INCLUDE_DARF_PAYMENT = 8;
    const SEARCH_DARF_PAYMENTS = 9;
    const INCLUDE_PAYMENT_BATCH = 10;
    const SEARCH_PAYMENT_BATCH = 11;
    const CANCEL_SCHEDULING = 12;
    const INCLUDE_PIX_KEY = 13;
    const GET_PIX = 14;
    const CREATE_WEBHOOK = 15;
    const GET_WEBHOOK = 16;
    const DELETE_WEBHOOK = 17;
    const RETRIEVE_CALLBACKS = 18;
    const RETRIEVE_CALLBACKS_PAGINATION = 19;
    public function showMenu(string $environment): int
    {
        echo "ENVIRONMENT: $environment\n";
        echo self::RETRIEVE_STATEMENT . " - Retrieve Bank Statement\n";
        echo self::RETRIEVE_STATEMENT_PDF . " - Retrieve Bank Statement PDF\n";
        echo self::RETRIEVE_ENRICHED_STATEMENT . " - Retrieve Enriched Bank Statement\n";
        echo self::RETRIEVE_ENRICHED_STATEMENT_PAGINATION . " - Retrieve Enriched Bank Statement with pagination\n";
        echo self::RETRIEVE_BALANCE . " - Retrieve Balance (current day)\n";
        echo self::INCLUDE_PAYMENT . " - Include Payment for billet\n";
        echo self::SEARCH_PAYMENTS . " - Search Payments for billet\n";
        echo self::INCLUDE_DARF_PAYMENT . " - Include DARF Payment\n";
        echo self::SEARCH_DARF_PAYMENTS . " - Search DARF Payments\n";
        echo self::INCLUDE_PAYMENT_BATCH . " - Include Payments in Batch\n";
        echo self::SEARCH_PAYMENT_BATCH . " - Search Payment Batch\n";
        echo self::CANCEL_SCHEDULING . " - Cancel payment scheduling\n";
        echo self::INCLUDE_PIX_KEY . " - Include Pix payment by Key\n";
        echo self::GET_PIX . " - Get Pix payment details\n";
        echo self::CREATE_WEBHOOK . " - Create Webhook\n";
        echo self::GET_WEBHOOK . " - Get Webhook\n";
        echo self::DELETE_WEBHOOK . " - Delete Webhook\n";
        echo self::RETRIEVE_CALLBACKS . " - Retrieve callbacks\n";
        echo self::RETRIEVE_CALLBACKS_PAGINATION . " - Retrieve callbacks with pagination\n";
        echo "0 - Exit\n";
        $choice = trim(fgets(STDIN));
        try {
            return (int)$choice;
        } catch (ValueError $e) {
            echo "Invalid option\n";
            return $this->showMenu($environment);
        }
    }

    /**
     * @throws SdkException
     * @throws JsonException
     */
    public function execute($op, $inter_sdk): void
    {
        $banking_functional_tests = new BankingFunctionalTests($inter_sdk);
        switch ($op) {
            case self::RETRIEVE_STATEMENT:
                $banking_functional_tests->testBankingStatement();
                break;
            case self::RETRIEVE_STATEMENT_PDF:
                $banking_functional_tests->testBankingStatementPdf();
                break;
            case self::RETRIEVE_ENRICHED_STATEMENT:
                $banking_functional_tests->testBankingEnrichedStatement();
                break;
            case self::RETRIEVE_ENRICHED_STATEMENT_PAGINATION:
                $banking_functional_tests->testBankingEnrichedStatementPage();
                break;
            case self::RETRIEVE_BALANCE:
                $banking_functional_tests->testBankingBalance();
                break;
            case self::INCLUDE_PAYMENT:
                $banking_functional_tests->testBankingIncludePayment();
                break;
            case self::SEARCH_PAYMENTS:
                $banking_functional_tests->testBankingRetrievePaymentList();
                break;
            case self::INCLUDE_DARF_PAYMENT:
                $banking_functional_tests->testBankingIncludeDarfPayment();
                break;
            case self::SEARCH_DARF_PAYMENTS:
                $banking_functional_tests->testBankingRetrieveDarfPayment();
                break;
            case self::INCLUDE_PAYMENT_BATCH:
                $banking_functional_tests->testBankingIncludePaymentBatch();
                break;
            case self::CANCEL_SCHEDULING:
                $banking_functional_tests->testBankingCancelPayment();
                break;
            case self::SEARCH_PAYMENT_BATCH:
                $banking_functional_tests->testBankingRetrievePaymentBatch();
                break;
            case self::INCLUDE_PIX_KEY:
                $banking_functional_tests->testBankingIncludePix();
                break;
            case self::GET_PIX:
                $banking_functional_tests->testBankingRetrievePix();
                break;
            case self::CREATE_WEBHOOK:
                $banking_functional_tests->testBankingIncludeWebhook();
                break;
            case self::GET_WEBHOOK:
                $banking_functional_tests->testBankingRetrieveWebhook();
                break;
            case self::DELETE_WEBHOOK:
                $banking_functional_tests->testBankingDeleteWebhook();
                break;
            case self::RETRIEVE_CALLBACKS:
                $banking_functional_tests->testBankingRetrieveCallbacks();
                break;
            case self::RETRIEVE_CALLBACKS_PAGINATION:
                $banking_functional_tests->testBankingRetrieveCallbackPaginated();
                break;
            default:
                echo "Exiting...\n";
                break;
        }
        echo "\n";
    }
}