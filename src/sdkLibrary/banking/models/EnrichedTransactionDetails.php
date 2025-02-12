<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The EnrichedTransactionDetails class represents additional details related to a transaction,
 * including the type of detail provided.
 */
class EnrichedTransactionDetails
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
        $obj = [
            "tipoDetalhe" => $this->detail_type
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "tipoDetalhe" => $this->detail_type
        ];
    }
}