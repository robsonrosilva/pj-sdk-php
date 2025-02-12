<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The Fees class represents the details of fees applied
 * to a transaction. It includes fields for the modality of the fees
 * (indicating the type or category) and the value or percentage
 * associated with the fees.
 */
class Fees
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