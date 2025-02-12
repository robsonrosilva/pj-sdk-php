<?php

namespace Inter\Sdk\sdkLibrary\pix\enums;

use ValueError;

enum BillingStatus: string
{
    /**
     * Active billing
     */
    case ATIVA = 'ATIVA';

    /**
     * Completed billing
     */
    case CONCLUIDA = 'CONCLUIDA';

    /**
     * Removed by the receiving user
     */
    case REMOVIDO_PELO_USUARIO_RECEBEDOR = 'REMOVIDO_PELO_USUARIO_RECEBEDOR';

    /**
     * Removed by the Payment Service Provider (PSP)
     */
    case REMOVIDO_PELO_PSP = 'REMOVIDO_PELO_PSP';

    /**
     * Create a BillingStatus instance from a string value.
     *
     * @param string $value The string representation of the BillingStatus.
     * @return BillingStatus The corresponding BillingStatus enum value.
     * @throws ValueError If the input string doesn't match any BillingStatus value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        try {
            return self::from($upperValue);
        } catch (\ValueError $e) {
            throw new \ValueError("'{$value}' is not a valid BillingStatus value");
        }
    }
}