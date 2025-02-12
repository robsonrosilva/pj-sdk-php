<?php

namespace Inter\Sdk\sdkLibrary\billing\enums;

use ValueError;

/**
 * The BillingSituation enum represents the different states or situations
 * that a billing can be in.
 */
enum BillingSituation: string
{
    /**
     * The payment has been received.
     */
    case RECEBIDO = 'RECEBIDO';

    /**
     * The payment is pending and expected to be received.
     */
    case A_RECEBER = 'A_RECEBER';

    /**
     * The payment has been marked as received.
     */
    case MARCADO_RECEBIDO = 'MARCADO_RECEBIDO';

    /**
     * The payment is overdue.
     */
    case ATRASADO = 'ATRASADO';

    /**
     * The billing has been canceled.
     */
    case CANCELADO = 'CANCELADO';

    /**
     * The billing has expired.
     */
    case EXPIRADO = 'EXPIRADO';

    /**
     * There was a failure in issuing the billing.
     */
    case FALHA_EMISSAO = 'FALHA_EMISSAO';

    /**
     * The billing is currently being processed.
     */
    case EM_PROCESSAMENTO = 'EM_PROCESSAMENTO';

    /**
     * Create a BillingSituation instance from a string value.
     *
     * @param string $value The string representation of the BillingSituation.
     * @return BillingSituation The corresponding BillingSituation enum value.
     * @throws ValueError If the input string doesn't match any BillingSituation.
     */
    public static function fromString(string $value): self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        throw new ValueError("'{$value}' is not a valid BillingSituation");
    }
}