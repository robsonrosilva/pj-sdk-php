<?php

namespace Inter\Sdk\sdkLibrary\billing\enums;

use ValueError;

/**
 * The BillingDateType enum represents the different types of dates that can be used in billing operations.
 */
enum BillingDateType: string
{
    /**
     * Represents the due date of the billing.
     */
    case VENCIMENTO = 'VENCIMENTO';

    /**
     * Represents the issue date of the billing.
     */
    case EMISSAO = 'EMISSAO';

    /**
     * Represents the payment date of the billing.
     */
    case PAGAMENTO = 'PAGAMENTO';

    /**
     * Create a BillingDateType instance from a string value.
     *
     * @param string $value The string representation of the BillingDateType.
     * @return BillingDateType The corresponding BillingDateType enum value.
     * @throws ValueError If the input string doesn't match any BillingDateType.
     */
    public static function fromString(string $value): self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        throw new ValueError("'{$value}' is not a valid BillingDateType");
    }
}