<?php

namespace Inter\Sdk\sdkLibrary\billing\enums;

use ValueError;

/**
 * The FineCode enum represents the different types of fines
 * that can be applied to a billing.
 */
enum FineCode: string
{
    /**
     * Indicates that no fine is applied.
     */
    case NAOTEMMULTA = 'NAOTEMMULTA';

    /**
     * Indicates a fixed amount fine.
     */
    case VALORFIXO = 'VALORFIXO';

    /**
     * Indicates a percentage-based fine.
     */
    case PERCENTUAL = 'PERCENTUAL';

    /**
     * Create a FineCode instance from a string value.
     *
     * @param string $value The string representation of the FineCode.
     * @return FineCode The corresponding FineCode enum value.
     * @throws ValueError If the input string doesn't match any FineCode.
     */
    public static function fromString(string $value): self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        throw new ValueError("'{$value}' is not a valid FineCode");
    }
}