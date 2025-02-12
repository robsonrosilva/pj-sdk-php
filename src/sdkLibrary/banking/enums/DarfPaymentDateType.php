<?php

namespace Inter\Sdk\sdkLibrary\banking\enums;

use ValueError;

/**
 * The DarfPaymentDateType enum represents the different types of dates
 * associated with a DARF (Documento de Arrecadação de Receitas Federais) payment.
 */
enum DarfPaymentDateType: string
{
    /**
     * Represents the inclusion date of the DARF payment.
     */
    case INCLUSAO = 'INCLUSAO';

    /**
     * Represents the payment date of the DARF.
     */
    case PAGAMENTO = 'PAGAMENTO';

    /**
     * Represents the due date of the DARF.
     */
    case VENCIMENTO = 'VENCIMENTO';

    /**
     * Represents the assessment period date of the DARF.
     */
    case PERIODO_APURACAO = 'PERIODO_APURACAO';

    /**
     * Create a DarfPaymentDateType instance from a string value.
     *
     * @param string $value The string representation of the DarfPaymentDateType.
     * @return DarfPaymentDateType The corresponding DarfPaymentDateType enum value.
     * @throws ValueError If the input string doesn't match any DarfPaymentDateType value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        foreach (self::cases() as $case) {
            if ($case->value === $upperValue) {
                return $case;
            }
        }

        throw new ValueError("'{$value}' is not a valid DarfPaymentDateType value");
    }
}