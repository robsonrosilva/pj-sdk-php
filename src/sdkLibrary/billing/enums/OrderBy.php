<?php

namespace Inter\Sdk\sdkLibrary\billing\enums;

use ValueError;

/**
 * The OrderBy enum represents the different fields by which
 * billing data can be ordered.
 */
enum OrderBy: string
{
    /**
     * Order by the payer.
     */
    case PESSOA_PAGADORA = 'PESSOA_PAGADORA';

    /**
     * Order by the type of billing.
     */
    case TIPO_COBRANCA = 'TIPO_COBRANCA';

    /**
     * Order by the billing code.
     */
    case CODIGO_COBRANCA = 'CODIGO_COBRANCA';

    /**
     * Order by the identifier.
     */
    case IDENTIFICADOR = 'IDENTIFICADOR';

    /**
     * Order by the issue date.
     */
    case DATA_EMISSAO = 'DATA_EMISSAO';

    /**
     * Order by the due date.
     */
    case DATA_VENCIMENTO = 'DATA_VENCIMENTO';

    /**
     * Order by the amount.
     */
    case VALOR = 'VALOR';

    /**
     * Order by the status.
     */
    case STATUS = 'STATUS';

    /**
     * Create an OrderBy instance from a string value.
     *
     * @param string $value The string representation of the OrderBy.
     * @return OrderBy The corresponding OrderBy enum value.
     * @throws ValueError If the input string doesn't match any OrderBy value.
     */
    public static function fromString(string $value): self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        throw new ValueError("'{$value}' is not a valid OrderBy value");
    }
}