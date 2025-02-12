<?php

namespace Inter\Sdk\functionalTests;

use Exception;
use Inter\Sdk\functionalTests\menus\BankingMenu;
use Inter\Sdk\functionalTests\menus\BillingMenu;
use Inter\Sdk\functionalTests\menus\PixMenu;
use Inter\Sdk\functionalTests\utils\FuncTestUtils;
use Inter\Sdk\sdkLibrary\commons\exceptions\InvalidEnvironmentException;
use Inter\Sdk\sdkLibrary\commons\exceptions\SdkException;
use Inter\Sdk\sdkLibrary\InterSdk;
use ValueError;

require_once '../../vendor/autoload.php';

/**
 * @throws InvalidEnvironmentException
 */
function validate_environment(string $environment): void
{
    /**
     * Validates the given environment string.
     *
     * @param string $environment The environment to validate.
     * @throws InvalidEnvironmentException If the environment is not valid.
     */
    $environments = ["PRODUCTION", "SANDBOX", "UAT"];

    if (!in_array(strtoupper($environment), $environments)) {
        throw new InvalidEnvironmentException();
    }
}

/**
 * @throws SdkException
 * @throws Exception
 */
function get_inter_sdk_data(string $environment): InterSdk
{
    /**
     * Retrieves InterSdk data based on the provided environment.
     *
     * @param string $environment The environment for the integration.
     * @return InterSdk An instance of InterSdk configured with provided credentials.
     * @throws SdkException If an error occurs during SDK operation.
     */
    $client_id = FuncTestUtils::getString("Integration clientId");
    $client_secret = FuncTestUtils::getString("Integration clientSecret");
    $certificate = FuncTestUtils::getString("Path of the file with the pfx certificate (ex: src/main/java/inter/certificates/production.pfx)");
    $password = FuncTestUtils::getString("Password of the file with the pfx certificate");
    $account = FuncTestUtils::getString("Account");

    $inter_sdk = new InterSdk($environment, $client_id, $client_secret, $certificate, $password);
    $inter_sdk->setAccount($account);

    $inter_sdk->setRateLimitControl(true);
    return $inter_sdk;
}

function menu(string $environment): int
{
    /**
     * Displays the main menu for the API options and returns the user's choice.
     *
     * @param string $environment The current environment (e.g. PRODUCTION, SANDBOX).
     * @return int The option selected by the user.
     */
    echo "ENVIRONMENT: $environment\n";
    echo "1 - API Billing\n";
    echo "2 - API Banking\n";
    echo "3 - API Pix\n";
    echo "0 - Exit\n";
    echo "=> ";

    $choice = trim(fgets(STDIN));

    try {
        return (int)$choice;
    } catch (ValueError $e) {
        echo "Invalid option\n";
        return menu($environment);
    }
}

class FunctionalTestsRunner
{


    /**
     * @throws SdkException
     * @throws InvalidEnvironmentException
     */
    public static function run(): void
    {
        /**
         * Main function to execute the menu-based interaction for the SDK.
         * @throws SdkException If an error occurs during SDK operations.
         */
        $environment = FuncTestUtils::getString("Environment (PRODUCTION, SANDBOX)");

        validate_environment($environment);
        $inter_sdk = get_inter_sdk_data($environment);

        while (($op = menu($environment)) !== 0) {
            try {
                if ($op === 1) {
                    $billing_menu = new BillingMenu();
                    while (($op = $billing_menu->showMenu($environment)) !== 0) {
                        $billing_menu->execute($op, $inter_sdk);
                    }
                } elseif ($op === 2) {
                    $banking_menu = new BankingMenu();
                    while (($op = $banking_menu->showMenu($environment)) !== 0) {
                        $banking_menu->execute($op, $inter_sdk);
                    }
                } elseif ($op === 3) {
                    $pix_menu = new PixMenu();
                    while (($op = $pix_menu->showMenu($environment)) !== 0) {
                        $pix_menu->execute($op, $inter_sdk);
                    }
                }
            } catch (Exception $e) {
                echo "An error occurred:\n";

                $title_detail = $e->getMessage() ?? "Error processing the request";

                echo $title_detail . "\n";
            }
        }
    }


}

FunctionalTestsRunner::run();