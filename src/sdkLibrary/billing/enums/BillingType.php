<?php

namespace Inter\Sdk\sdkLibrary\billing\enums;


use ValueError;

/**
 * The BillingType enum represents the different types of billing
 * that can be applied.
 */
enum BillingType: string
{
    /**
     * Represents a simple, one-time billing.
     */
    case SIMPLES = 'SIMPLES';

    /**
     * Represents a billing that is paid in installments.
     */
    case PARCELADO = 'PARCELADO';

    /**
     * Represents a recurring billing that repeats at regular intervals.
     */
    case RECORRENTE = 'RECORRENTE';

    /**
     * Create a BillingType instance from a string value.
     *
     * @param string $value The string representation of the BillingType.
     * @return BillingType The corresponding BillingType enum value.
     * @throws ValueError If the input string doesn't match any BillingType.
     */
    public static function fromString(string $value): self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        throw new ValueError("'{$value}' is not a valid BillingType");
    }
}