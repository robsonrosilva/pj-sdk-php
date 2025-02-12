<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The CallbackRetrieveFilter class represents a filter for retrieving callbacks,
 * including transaction code and end-to-end ID.
 */
class CallbackRetrieveFilter
{
    private ?string $transaction_code;
    private ?string $end_to_end_id;
    
    public function __construct(
        ?string $transaction_code = null,
        ?string $end_to_end_id = null
    ) {
        $this->transaction_code = $transaction_code;
        $this->end_to_end_id = $end_to_end_id;
    }

    public function getTransactionCode(): ?string
    {
        return $this->transaction_code;
    }

    public function setTransactionCode(?string $transaction_code): void
    {
        $this->transaction_code = $transaction_code;
    }

    public function getEndToEndId(): ?string
    {
        return $this->end_to_end_id;
    }

    public function setEndToEndId(?string $end_to_end_id): void
    {
        $this->end_to_end_id = $end_to_end_id;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['codigoTransacao'] ?? null,
            $json['endToEnd'] ?? null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "codigoTransacao" => $this->transaction_code,
            "endToEnd" => $this->end_to_end_id,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "codigoTransacao" => $this->transaction_code,
            "endToEnd" => $this->end_to_end_id,
        ];
    }
}