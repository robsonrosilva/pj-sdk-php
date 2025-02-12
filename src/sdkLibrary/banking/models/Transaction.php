<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The Transaction class represents a financial transaction with
 * various details such as type, value, and description.
 */
class Transaction
{
    private ?string $cpmf;
    private ?string $entry_date;
    private ?string $transaction_type;
    private ?string $operation_type;
    private ?string $value;
    private ?string $title;
    private ?string $description;

    public function __construct(
        ?string $cpmf = null,
        ?string $entry_date = null,
        ?string $transaction_type = null,
        ?string $operation_type = null,
        ?string $value = null,
        ?string $title = null,
        ?string $description = null
    ) {
        $this->cpmf = $cpmf;
        $this->entry_date = $entry_date;
        $this->transaction_type = $transaction_type;
        $this->operation_type = $operation_type;
        $this->value = $value;
        $this->title = $title;
        $this->description = $description;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['cpmf'] ?? null,
            $json['dataEntrada'] ?? null,
            $json['tipoTransacao'] ?? null,
            $json['tipoOperacao'] ?? null,
            $json['valor'] ?? null,
            $json['titulo'] ?? null,
            $json['descricao'] ?? null
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
            "cpmf" => $this->cpmf,
            "dataEntrada" => $this->entry_date,
            "tipoTransacao" => $this->transaction_type,
            "tipoOperacao" => $this->operation_type,
            "valor" => $this->value,
            "titulo" => $this->title,
            "descricao" => $this->description
        ];
    }
}