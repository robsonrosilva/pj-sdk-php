<?php

namespace Inter\Sdk\sdkLibrary\pix\enums;

use ValueError;

/**
 * The DevolutionNature enum represents the nature of a PIX devolution.
 */
enum DevolutionNature: string
{
    /**
     * Original devolution (return of the original transaction)
     */
    case ORIGINAL = 'ORIGINAL';

    /**
     * Withdrawal (cancellation or reversal of a transaction)
     */
    case RETIRADA = 'RETIRADA';

    /**
     * Create a DevolutionNature instance from a string value.
     *
     * @param string $value The string representation of the DevolutionNature.
     * @return DevolutionNature The corresponding DevolutionNature enum value.
     * @throws ValueError If the input string doesn't match any DevolutionNature value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        try {
            return self::from($upperValue);
        } catch (\ValueError $e) {
            throw new \ValueError("'{$value}' is not a valid DevolutionNature value");
        }
    }
}