<?php

namespace Inter\Sdk\sdkLibrary\banking\enums;

/**
 * The PaymentDateType enum represents the different types of dates
 * associated with a payment.
 */
enum PaymentDateType: string
{
    /**
     * Represents the inclusion date of the payment.
     */
    case INCLUSAO = 'INCLUSAO';

    /**
     * Represents the actual payment date.
     */
    case PAGAMENTO = 'PAGAMENTO';

    /**
     * Represents the due date of the payment.
     */
    case VENCIMENTO = 'VENCIMENTO';

    /**
     * Create a PaymentDateType instance from a string value.
     *
     * @param string $value The string representation of the PaymentDateType.
     * @return PaymentDateType The corresponding PaymentDateType enum value.
     * @throws \ValueError If the input string doesn't match any PaymentDateType value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        foreach (self::cases() as $case) {
            if ($case->value === $upperValue) {
                return $case;
            }
        }

        throw new \ValueError("'{$value}' is not a valid PaymentDateType value");
    }

    /**
     * Get a human-readable description of the payment date type.
     *
     * @return string A description of the payment date type.
     */
    public function description(): string
    {
        return match($this) {
            self::INCLUSAO => 'Inclusion Date',
            self::PAGAMENTO => 'Payment Date',
            self::VENCIMENTO => 'Due Date',
        };
    }

    public function toString(): string
    {
        return $this->value;
    }
}