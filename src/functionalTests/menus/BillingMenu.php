<?php

namespace Inter\Sdk\functionalTests\menus;

use Inter\Sdk\functionalTests\BillingFunctionalTests;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use ValueError;

class BillingMenu
{
    const ISSUE_BILLING = 1;
    const RETRIEVE_BILLINGS = 2;
    const RETRIEVE_BILLING_PAGINATION = 3;
    const RETRIEVE_BILLING_SUMMARY = 4;
    const RETRIEVE_DETAILED_BILLING = 5;
    const RETRIEVE_BILLING_PDF = 6;
    const CANCEL_BILLING = 7;
    const CREATE_WEBHOOK = 8;
    const GET_WEBHOOK = 9;
    const DELETE_WEBHOOK = 10;
    const RETRIEVE_CALLBACKS = 11;
    const RETRIEVE_CALLBACKS_PAGINATION = 12;

    public function showMenu(string $environment): int
    {
        echo "ENVIRONMENT: $environment\n";
        echo self::ISSUE_BILLING . " - Issue Billing\n";
        echo self::RETRIEVE_BILLINGS . " - Retrieve Billings\n";
        echo self::RETRIEVE_BILLING_PAGINATION . " - Retrieve Billing with pagination\n";
        echo self::RETRIEVE_BILLING_SUMMARY . " - Retrieve Billing Summary\n";
        echo self::RETRIEVE_DETAILED_BILLING . " - Retrieve Detailed Billing\n";
        echo self::RETRIEVE_BILLING_PDF . " - Retrieve Billing PDF\n";
        echo self::CANCEL_BILLING . " - Cancel Billing\n";
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
     */
    public function execute($op, $inter_sdk): void
    {
        $billing_functional_tests = new BillingFunctionalTests($inter_sdk);

        switch ($op) {
            case self::ISSUE_BILLING:
                $billing_functional_tests->testBillingIssueBilling();
                break;
            case self::RETRIEVE_BILLINGS:
                $billing_functional_tests->testBillingRetrieveBillingCollection();
                break;
            case self::RETRIEVE_BILLING_PAGINATION:
                $billing_functional_tests->testBillingRetrieveBillingCollectionPage();
                break;
            case self::RETRIEVE_BILLING_SUMMARY:
                $billing_functional_tests->testBillingRetrieveBillingSummary();
                break;
            case self::RETRIEVE_DETAILED_BILLING:
                $billing_functional_tests->testBillingRetrieveBilling();
                break;
            case self::RETRIEVE_BILLING_PDF:
                $billing_functional_tests->testBillingRetrieveBillingPdf();
                break;
            case self::CANCEL_BILLING:
                $billing_functional_tests->testBillingCancelBilling();
                break;
            case self::CREATE_WEBHOOK:
                $billing_functional_tests->testBillingIncludeWebhook();
                break;
            case self::GET_WEBHOOK:
                $billing_functional_tests->testBillingRetrieveWebhook();
                break;
            case self::DELETE_WEBHOOK:
                $billing_functional_tests->testBillingDeleteWebhook();
                break;
            case self::RETRIEVE_CALLBACKS:
                $billing_functional_tests->testBillingRetrieveCallbacks();
                break;
            case self::RETRIEVE_CALLBACKS_PAGINATION:
                $billing_functional_tests->testBillingRetrieveCallbacksPage();
                break;
            default:
                echo "Exiting...\n";
                break;
        }

        echo "\n";
    }
}