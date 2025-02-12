<?php

namespace Inter\Sdk\sdkLibrary\billing\enums;

use ValueError;

/**
 * The ReceivingOrigin enum represents the different methods
 * through which a payment can be received.
 */
enum ReceivingOrigin: string
{
    /**
     * Represents payment received through a bank slip (boleto).
     */
    case BOLETO = 'BOLETO';

    /**
     * Represents payment received through the PIX instant payment system.
     */
    case PIX = 'PIX';

    /**
     * Create a ReceivingOrigin instance from a string value.
     *
     * @param string $value The string representation of the ReceivingOrigin.
     * @return ReceivingOrigin The corresponding ReceivingOrigin enum value.
     * @throws ValueError If the input string doesn't match any ReceivingOrigin value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        try {
            return self::from($upperValue);
        } catch (\ValueError $e) {
            throw new \ValueError("'{$value}' is not a valid ReceivingOrigin value");
        }
    }
}