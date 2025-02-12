<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The Reduction class represents the details of a discount
 * applicable to a transaction. It includes fields for the modality
 * of the discount (indicating the type or category) and the value or
 * percentage of the discount applied. This structure is important for
 * managing financial discounts within a system.
 */
class Reduction
{
    private ?int $modality;
    private ?string $value_percentage;

    public function __construct(
        ?int $modality = null,
        ?string $value_percentage = null
    ) {
        $this->modality = $modality;
        $this->value_percentage = $value_percentage;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['modalidade'] ?? null,
            $data['valorPerc'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "modalidade" => $this->modality,
            "valorPerc" => $this->value_percentage
        ];
    }
}