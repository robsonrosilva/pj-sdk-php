<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The EnrichedTransaction class represents a transaction with enriched details,
 * including identifiers, amounts, and more specific transaction details.
 */
class EnrichedTransaction
{
    private ?string $cpmf;
    private ?string $transaction_id;
    private ?string $inclusion_date;
    private ?string $transaction_date;
    private ?string $transaction_type;
    private ?string $operation_type;
    private ?string $value;
    private ?string $title;
    private ?string $description;
    private ?EnrichedTransactionDetails $details;

    public function __construct(
        ?string $cpmf = null,
        ?string $transaction_id = null,
        ?string $inclusion_date = null,
        ?string $transaction_date = null,
        ?string $transaction_type = null,
        ?string $operation_type = null,
        ?string $value = null,
        ?string $title = null,
        ?string $description = null,
        ?EnrichedTransactionDetails $details = null
    ) {
        $this->cpmf = $cpmf;
        $this->transaction_id = $transaction_id;
        $this->inclusion_date = $inclusion_date;
        $this->transaction_date = $transaction_date;
        $this->transaction_type = $transaction_type;
        $this->operation_type = $operation_type;
        $this->value = $value;
        $this->title = $title;
        $this->description = $description;
        $this->details = $details;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['cpmf'] ?? null,
            $json['idTransacao'] ?? null,
            $json['dataInclusao'] ?? null,
            $json['dataTransacao'] ?? null,
            $json['tipoTransacao'] ?? null,
            $json['tipoOperacao'] ?? null,
            $json['valor'] ?? null,
            $json['titulo'] ?? null,
            $json['descricao'] ?? null,
            isset($json['detalhes']) ? EnrichedTransactionDetails::fromJson($json['detalhes']) : null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "cpmf" => $this->cpmf,
            "idTransacao" => $this->transaction_id,
            "dataInclusao" => $this->inclusion_date,
            "dataTransacao" => $this->transaction_date,
            "tipoTransacao" => $this->transaction_type,
            "tipoOperacao" => $this->operation_type,
            "valor" => $this->value,
            "titulo" => $this->title,
            "descricao" => $this->description,
            "detalhes" => $this->details?->toJson(),
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "cpmf" => $this->cpmf,
            "idTransacao" => $this->transaction_id,
            "dataInclusao" => $this->inclusion_date,
            "dataTransacao" => $this->transaction_date,
            "tipoTransacao" => $this->transaction_type,
            "tipoOperacao" => $this->operation_type,
            "valor" => $this->value,
            "titulo" => $this->title,
            "descricao" => $this->description,
            "detalhes" => $this->details?->toArray(),
        ];
    }
}