<?php

namespace Inter\Sdk\functionalTests\menus;

use DateMalformedStringException;
use Inter\Sdk\functionalTests\PixFunctionalTests;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use JsonException;
use ValueError;

class PixMenu
{
    const CREATE_IMMEDIATE_BILLING = 1;
    const CREATE_IMMEDIATE_BILLING_TXID = 2;
    const REVIEW_IMMEDIATE_BILLING = 3;
    const RETRIEVE_IMMEDIATE_BILLING = 4;
    const RETRIEVE_IMMEDIATE_BILLINGS = 5;
    const RETRIEVE_IMMEDIATE_BILLINGS_PAGINATION = 6;
    const CREATE_DUE_BILLING = 7;
    const REVIEW_DUE_BILLING = 8;
    const RETRIEVE_DUE_BILLING = 9;
    const RETRIEVE_DUE_BILLINGS = 10;
    const RETRIEVE_DUE_BILLINGS_PAGINATION = 11;
    const CREATE_LOCATION = 12;
    const RETRIEVE_LOCATION = 13;
    const UNLINK_LOCATION = 14;
    const RETRIEVE_REGISTERED_LOCATIONS = 15;
    const RETRIEVE_REGISTERED_LOCATIONS_PAGINATION = 16;
    const RETRIEVE_PIX = 17;
    const RETRIEVE_RECEIVED_PIX = 18;
    const RETRIEVE_RECEIVED_PIX_PAGINATION = 19;
    const REQUEST_PIX_DEVOLUTION = 20;
    const RETRIEVE_PIX_DEVOLUTION = 21;
    const RETRIEVE_DUE_BILLING_BATCH = 22;
    const RETRIEVE_DUE_BILLING_BATCH_PAGINATION = 23;
    const RETRIEVE_DUE_BILLING_BATCHES = 24;
    const RETRIEVE_DUE_BILLING_BATCH_SUMMARY = 25;
    const RETRIEVE_DUE_BILLING_BATCH_SITUATION = 26;
    const CREATE_DUE_BILLING_BATCH = 27;
    const REVIEW_DUE_BILLING_BATCH = 28;
    const CREATE_WEBHOOK = 29;
    const GET_WEBHOOK = 30;
    const DELETE_WEBHOOK = 31;
    const RETRIEVE_CALLBACKS = 32;
    const RETRIEVE_CALLBACKS_PAGINATION = 33;
    public function showMenu(string $environment): int
    {
        echo "ENVIRONMENT: $environment\n";
        echo self::CREATE_IMMEDIATE_BILLING . " - Create Immediate Billing\n";
        echo self::CREATE_IMMEDIATE_BILLING_TXID . " - Create Immediate Billing TxId\n";
        echo self::REVIEW_IMMEDIATE_BILLING . " - Review Immediate Billing\n";
        echo self::RETRIEVE_IMMEDIATE_BILLING . " - Retrieve Immediate Billing by TxId\n";
        echo self::RETRIEVE_IMMEDIATE_BILLINGS . " - Retrieve Immediate Billings\n";
        echo self::RETRIEVE_IMMEDIATE_BILLINGS_PAGINATION . " - Retrieve Immediate Billings with pagination\n";
        echo self::CREATE_DUE_BILLING . " - Create Due Billing\n";
        echo self::REVIEW_DUE_BILLING . " - Review Due Billing\n";
        echo self::RETRIEVE_DUE_BILLING . " - Retrieve Due Billing by TxId\n";
        echo self::RETRIEVE_DUE_BILLINGS . " - Retrieve Due Billings\n";
        echo self::RETRIEVE_DUE_BILLINGS_PAGINATION . " - Retrieve Due Billings with pagination\n";
        echo self::CREATE_LOCATION . " - Create Location\n";
        echo self::RETRIEVE_LOCATION . " - Retrieve Location\n";
        echo self::UNLINK_LOCATION . " - Unlink Location\n";
        echo self::RETRIEVE_REGISTERED_LOCATIONS . " - Retrieve Registered Locations\n";
        echo self::RETRIEVE_REGISTERED_LOCATIONS_PAGINATION . " - Retrieve Registered Locations with pagination\n";
        echo self::RETRIEVE_PIX . " - Retrieve Pix by e2eId\n";
        echo self::RETRIEVE_RECEIVED_PIX . " - Retrieve Received Pix\n";
        echo self::RETRIEVE_RECEIVED_PIX_PAGINATION . " - Retrieve Received Pix with pagination\n";
        echo self::REQUEST_PIX_DEVOLUTION . " - Request Devolution\n";
        echo self::RETRIEVE_PIX_DEVOLUTION . " - Retrieve Devolution\n";
        echo self::RETRIEVE_DUE_BILLING_BATCH . " - Retrieve Due Billing Batch\n";
        echo self::RETRIEVE_DUE_BILLING_BATCH_PAGINATION . " - Retrieve Due Billing Batch - Pagination\n";
        echo self::RETRIEVE_DUE_BILLING_BATCHES . " - Retrieve Due Billing Batches\n";
        echo self::RETRIEVE_DUE_BILLING_BATCH_SUMMARY . " - Retrieve Due Billing Batch - Summary\n";
        echo self::RETRIEVE_DUE_BILLING_BATCH_SITUATION . " - Retrieve Due Billing Batch - Situation\n";
        echo self::CREATE_DUE_BILLING_BATCH . " - Create Due Billing Batch\n";
        echo self::REVIEW_DUE_BILLING_BATCH . " - Review Due Billing Batch\n";
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
     * @throws DateMalformedStringException
     * @throws JsonException
     * @throws SdkException
     */
    public function execute($op, $inter_sdk): void
    {
        $pix_functional_tests = new PixFunctionalTests($inter_sdk);
        switch ($op) {
            case self::CREATE_IMMEDIATE_BILLING:
                $pix_functional_tests->testPixIncludeImmediateBilling();
                break;
            case self::REVIEW_IMMEDIATE_BILLING:
                $pix_functional_tests->testPixReviewImmediateBilling();
                break;
            case self::RETRIEVE_IMMEDIATE_BILLING:
                $pix_functional_tests->testPixRetrieveImmediateBilling();
                break;
            case self::CREATE_IMMEDIATE_BILLING_TXID:
                $pix_functional_tests->testPixIncludeImmediateBillingTxId();
                break;
            case self::RETRIEVE_IMMEDIATE_BILLINGS:
                $pix_functional_tests->testPixRetrieveImmediateBillingCollection();
                break;
            case self::RETRIEVE_IMMEDIATE_BILLINGS_PAGINATION:
                $pix_functional_tests->testPixRetrieveImmediateBillingCollectionPage();
                break;
            case self::CREATE_DUE_BILLING:
                $pix_functional_tests->testPixIncludeDueBilling();
                break;
            case self::REVIEW_DUE_BILLING:
                $pix_functional_tests->testPixReviewDueBilling();
                break;
            case self::RETRIEVE_DUE_BILLING:
                $pix_functional_tests->testPixRetrieveDueBilling();
                break;
            case self::RETRIEVE_DUE_BILLINGS:
                $pix_functional_tests->testPixRetrieveDueBillingCollection();
                break;
            case self::RETRIEVE_DUE_BILLINGS_PAGINATION:
                $pix_functional_tests->testPixRetrieveDueBillingCollectionPage();
                break;
            case self::CREATE_LOCATION:
                $pix_functional_tests->testPixIncludeLocation();
                break;
            case self::RETRIEVE_REGISTERED_LOCATIONS:
                $pix_functional_tests->testPixRetrieveLocationList();
                break;
            case self::RETRIEVE_REGISTERED_LOCATIONS_PAGINATION:
                $pix_functional_tests->testPixRetrieveLocationListPage();
                break;
            case self::RETRIEVE_LOCATION:
                $pix_functional_tests->testPixRetrieveLocation();
                break;
            case self::UNLINK_LOCATION:
                $pix_functional_tests->testPixUnlinkLocation();
                break;
            case self::RETRIEVE_PIX:
                $pix_functional_tests->testPixRetrievePix();
                break;
            case self::RETRIEVE_RECEIVED_PIX:
                $pix_functional_tests->testPixRetrievePixList();
                break;
            case self::RETRIEVE_RECEIVED_PIX_PAGINATION:
                $pix_functional_tests->testPixRetrievePixListPage();
                break;
            case self::REQUEST_PIX_DEVOLUTION:
                $pix_functional_tests->testPixRequestDevolution();
                break;
            case self::RETRIEVE_PIX_DEVOLUTION:
                $pix_functional_tests->testPixRetrieveDevolution();
                break;
            case self::RETRIEVE_DUE_BILLING_BATCH:
                $pix_functional_tests->testPixRetrieveDueBillingBatch();
                break;
            case self::RETRIEVE_DUE_BILLING_BATCH_PAGINATION:
                $pix_functional_tests->testPixRetrieveDueBillingBatchCollectionPage();
                break;
            case self::RETRIEVE_DUE_BILLING_BATCHES:
                $pix_functional_tests->testPixRetrieveDueBillingBatchCollection();
                break;
            case self::RETRIEVE_DUE_BILLING_BATCH_SUMMARY:
                $pix_functional_tests->testPixRetrieveDueBillingBatchSummary();
                break;
            case self::RETRIEVE_DUE_BILLING_BATCH_SITUATION:
                $pix_functional_tests->testPixRetrieveDueBillingBatchBySituation();
                break;
            case self::CREATE_DUE_BILLING_BATCH:
                $pix_functional_tests->testPixIncludeDueBillingBatch();
                break;
            case self::REVIEW_DUE_BILLING_BATCH:
                $pix_functional_tests->testPixReviewDueBillingBatch();
                break;
            case self::CREATE_WEBHOOK:
                $pix_functional_tests->testBillingIncludeWebhook();
                break;
            case self::GET_WEBHOOK:
                $pix_functional_tests->testBillingRetrieveWebhook();
                break;
            case self::DELETE_WEBHOOK:
                $pix_functional_tests->testBillingDeleteWebhook();
                break;
            case self::RETRIEVE_CALLBACKS:
                $pix_functional_tests->testBillingRetrieveCallbacks();
                break;
            case self::RETRIEVE_CALLBACKS_PAGINATION:
                $pix_functional_tests->testBillingRetrieveCallbacksPage();
                break;
            default:
                echo "Exiting...\n";
                break;
        }
        echo "\n";
    }
}