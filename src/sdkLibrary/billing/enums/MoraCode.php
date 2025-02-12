<?php

namespace Inter\Sdk\sdkLibrary\billing\enums;

use ValueError;

/**
 * The MoraCode enum represents the different types of late payment interest
 * that can be applied to a billing.
 */
enum MoraCode: string
{
    /**
     * Indicates a fixed daily amount for late payment interest.
     */
    case VALORDIA = 'VALORDIA';

    /**
     * Indicates a monthly rate for late payment interest.
     */
    case TAXAMENSAL = 'TAXAMENSAL';

    /**
     * Indicates that no late payment interest is applied.
     */
    case ISENTO = 'ISENTO';

    /**
     * Indicates that the late payment interest is controlled by the bank.
     */
    case CONTROLEDOBANCO = 'CONTROLEDOBANCO';

    /**
     * Create a MoraCode instance from a string value.
     *
     * @param string $value The string representation of the MoraCode.
     * @return MoraCode The corresponding MoraCode enum value.
     * @throws ValueError If the input string doesn't match any MoraCode.
     */
    public static function fromString(string $value): self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        throw new ValueError("'{$value}' is not a valid MoraCode");
    }
}