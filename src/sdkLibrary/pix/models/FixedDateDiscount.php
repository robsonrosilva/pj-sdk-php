<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The FixedDateDiscount class represents a discount
 * that applies to a specific date. It includes fields for
 * the percentage value of the discount and the associated date.
 * This structure is useful for managing fixed-date discounts
 * within a financial or sales system.
 */
class FixedDateDiscount
{
    private ?string $value_percentage;
    private ?string $date;

    public function __construct(
        ?string $value_percentage = null,
        ?string $date = null
    ) {
        $this->value_percentage = $value_percentage;
        $this->date = $date;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['valorPerc'] ?? null,
            $data['data'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "valorPerc" => $this->value_percentage,
            "data" => $this->date
        ];
    }
}