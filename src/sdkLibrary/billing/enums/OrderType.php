<?php

namespace Inter\Sdk\sdkLibrary\billing\enums;

use ValueError;

/**
 * The OrderType enum represents the direction of ordering
 * that can be applied to sorting operations.
 */
enum OrderType: string
{
    /**
     * Represents ascending order.
     */
    case ASC = 'ASC';

    /**
     * Represents descending order.
     */
    case DESC = 'DESC';

    /**
     * Create an OrderType instance from a string value.
     *
     * @param string $value The string representation of the OrderType.
     * @return OrderType The corresponding OrderType enum value.
     * @throws ValueError If the input string doesn't match any OrderType value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        try {
            return self::from($upperValue);
        } catch (\ValueError $e) {
            throw new \ValueError("'{$value}' is not a valid OrderType value");
        }
    }
}