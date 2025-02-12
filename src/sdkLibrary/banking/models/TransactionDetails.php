<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The TransactionDetails class represents the details of a financial
 * transaction, including the type of detail.
 */
class TransactionDetails
{
    private ?string $detail_type;

    public function __construct(?string $detail_type = null)
    {
        $this->detail_type = $detail_type;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['tipoDetalhe'] ?? null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "tipoDetalhe" => $this->detail_type
        ];
    }
}