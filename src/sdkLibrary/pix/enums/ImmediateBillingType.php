<?php

namespace Inter\Sdk\sdkLibrary\pix\enums;

use ValueError;

/**
 * The ImmediateBillingType enum represents the types of immediate PIX billing.
 */
enum ImmediateBillingType: string
{
    /**
     * Cobrança imediata (Immediate billing)
     */
    case cob = 'cob';

    /**
     * Cobrança com vencimento (Billing with due date)
     */
    case cobv = 'cobv';

    /**
     * Create an ImmediateBillingType instance from a string value.
     *
     * @param string $value The string representation of the ImmediateBillingType.
     * @return ImmediateBillingType The corresponding ImmediateBillingType enum value.
     * @throws ValueError If the input string doesn't match any ImmediateBillingType value.
     */
    public static function fromString(string $value): self
    {
        $lowerValue = strtolower($value);

        try {
            return self::from($lowerValue);
        } catch (\ValueError $e) {
            throw new \ValueError("'{$value}' is not a valid ImmediateBillingType value");
        }
    }
}