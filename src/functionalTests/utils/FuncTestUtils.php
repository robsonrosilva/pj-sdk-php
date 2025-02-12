<?php

namespace Inter\Sdk\functionalTests\utils;

class FuncTestUtils
{
    /**
     * Prompts the user for input and returns it as a string.
     *
     * @param string $prompt The prompt message to display.
     * @return string The user input as a string.
     */
    public static function getString(string $prompt): string
    {
        echo $prompt . ": ";
        return trim(fgets(STDIN));
    }

    /**
     * Prompts the user for input and returns it as a float (PHP's closest equivalent to BigDecimal).
     *
     * @param string $prompt The prompt message to display.
     * @return float The user input as a float.
     */
    public static function getBigDecimal(string $prompt): float
    {
        echo $prompt . ": ";
        $input = trim(fgets(STDIN));
        return (float) $input;
    }
}