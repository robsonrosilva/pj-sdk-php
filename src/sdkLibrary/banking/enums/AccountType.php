<?php

namespace Inter\Sdk\sdkLibrary\banking\enums;

/**
 * The AccountType enum represents the different types of bank accounts.
 */
enum AccountType: string
{
    /**
     * Represents a checking account.
     */
    case CONTA_CORRENTE = 'CONTA_CORRENTE';

    /**
     * Represents a savings account.
     */
    case CONTA_POUPANCA = 'CONTA_POUPANCA';

    /**
     * Represents a salary account.
     */
    case CONTA_SALARIO = 'CONTA_SALARIO';

    /**
     * Represents a payment account.
     */
    case CONTA_PAGAMENTO = 'CONTA_PAGAMENTO';

    /**
     * Create an AccountType instance from a string value.
     *
     * @param string $value The string representation of the AccountType.
     * @return AccountType The corresponding AccountType enum value.
     * @throws \ValueError If the input string doesn't match any AccountType value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        foreach (self::cases() as $case) {
            if ($case->value === $upperValue) {
                return $case;
            }
        }

        throw new \ValueError("'{$value}' is not a valid AccountType value");
    }
}