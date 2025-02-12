<?php

namespace Inter\Sdk\sdkLibrary\banking\enums;

/**
 * The OperationType enum represents the types of financial operations.
 */
enum OperationType: string
{
    /**
     * Represents a debit operation.
     */
    case D = 'D';

    /**
     * Represents a credit operation.
     */
    case C = 'C';

    /**
     * Create an OperationType instance from a string value.
     *
     * @param string $value The string representation of the OperationType.
     * @return OperationType The corresponding OperationType enum value.
     * @throws \ValueError If the input string doesn't match any OperationType value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        foreach (self::cases() as $case) {
            if ($case->value === $upperValue) {
                return $case;
            }
        }

        throw new \ValueError("'{$value}' is not a valid OperationType value");
    }

    /**
     * Get a human-readable description of the operation type.
     *
     * @return string A description of the operation type.
     */
    public function description(): string
    {
        return match($this) {
            self::D => 'Debit',
            self::C => 'Credit',
        };
    }

    public function toString(): string
    {
        return $this->value;
    }
}