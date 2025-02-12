<?php

namespace Inter\Sdk\sdkLibrary\billing\enums;

use ValueError;

/**
 * The PersonType enum represents the types of legal entities
 * that can be involved in a billing transaction.
 */
enum PersonType: string
{
    /**
     * Represents a natural person (individual).
     */
    case FISICA = 'FISICA';

    /**
     * Represents a legal entity (company or organization).
     */
    case JURIDICA = 'JURIDICA';

    /**
     * Create a PersonType instance from a string value.
     *
     * @param string $value The string representation of the PersonType.
     * @return PersonType The corresponding PersonType enum value.
     * @throws ValueError If the input string doesn't match any PersonType value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        try {
            return self::from($upperValue);
        } catch (\ValueError $e) {
            throw new \ValueError("'{$value}' is not a valid PersonType value");
        }
    }
}