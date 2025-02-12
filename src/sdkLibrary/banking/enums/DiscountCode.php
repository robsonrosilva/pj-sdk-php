<?php

namespace Inter\Sdk\sdkLibrary\banking\enums;

/**
 * The DiscountCode enum represents the different types of discounts
 * that can be applied to a payment.
 */
enum DiscountCode: string
{
    /**
     * Indicates that no discount is applied.
     */
    case NAOTEMDESCONTO = 'NAOTEMDESCONTO';

    /**
     * Indicates a fixed value discount on a specified date.
     */
    case VALORFIXODATAINFORMADA = 'VALORFIXODATAINFORMADA';

    /**
     * Indicates a percentage discount on a specified date.
     */
    case PERCENTUALDATAINFORMADA = 'PERCENTUALDATAINFORMADA';

    /**
     * Indicates a fixed value discount for early payment on a business day.
     */
    case VALORANTECIPACAODIAUTIL = 'VALORANTECIPACAODIAUTIL';

    /**
     * Indicates a percentage discount based on the nominal value per calendar day.
     */
    case PERCENTUALVALORNOMINALDIACORRIDO = 'PERCENTUALVALORNOMINALDIACORRIDO';

    /**
     * Indicates a percentage discount based on the nominal value per business day.
     */
    case PERCENTUALVALORNOMINALDIAUTIL = 'PERCENTUALVALORNOMINALDIAUTIL';

    /**
     * Create a DiscountCode instance from a string value.
     *
     * @param string $value The string representation of the DiscountCode.
     * @return DiscountCode The corresponding DiscountCode enum value.
     * @throws \ValueError If the input string doesn't match any DiscountCode value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        foreach (self::cases() as $case) {
            if ($case->value === $upperValue) {
                return $case;
            }
        }

        throw new \ValueError("'{$value}' is not a valid DiscountCode value");
    }
}