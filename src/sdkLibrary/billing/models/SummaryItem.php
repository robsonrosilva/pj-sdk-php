<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

/**
 * The SummaryItem class represents a summary item in a billing context.
 *
 * It includes fields to capture the status of the item, the
 * quantity of items, and the monetary value associated with it.
 * This structure is useful for summarizing detailed billing information.
 */
class SummaryItem
{
    private ?string $situation;
    private ?int $quantity;
    private ?float $value;

    public function __construct(
        ?string $situation = null,
        ?int $quantity = null,
        ?float $value = null
    ) {
        $this->situation = $situation;
        $this->quantity = $quantity;
        $this->value = $value;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['situacao'] ?? null,
            $data['quantidade'] ?? null,
            isset($data['valor']) ? ($data['valor']) : null
        );
    }

    public function toArray(): array
    {
        return [
            "situacao" => $this->situation,
            "quantidade" => $this->quantity,
            "valor" => $this->value ? floatval($this->value) : null
        ];
    }
}