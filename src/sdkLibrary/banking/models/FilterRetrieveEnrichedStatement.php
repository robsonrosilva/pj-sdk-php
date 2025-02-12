<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use Inter\Sdk\sdkLibrary\banking\enums\OperationType;
use Inter\Sdk\sdkLibrary\banking\enums\TransactionType;

/**
 * The FilterRetrieveEnrichedStatement class represents the filters used to retrieve
 * enriched bank statements based on operation and transaction types.
 */
class FilterRetrieveEnrichedStatement
{
    private ?string $operation_type;
    private ?string $transaction_type;

    public function getTransactionType(): ?string
    {
        return $this->transaction_type;
    }

    public function getOperationType(): ?string
    {
        return $this->operation_type;
    }

    public function __construct(?string $operation_type = null, ?string $transaction_type = null)
    {
        $this->operation_type = $operation_type;
        $this->transaction_type = $transaction_type;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            isset($json['operationType']) ? OperationType::fromString($json['operationType'])->name : null,
            isset($json['transactionType']) ? TransactionType::fromString($json['transactionType'])->name : null
        );
    }

    public function toJson(): string
    {
        $obj = [
            "operationType" => $this->operation_type,
            "transactionType" => $this->transaction_type,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT);
    }

    public function toArray(): array
    {
        return [
            "operationType" => $this->operation_type,
            "transactionType" => $this->transaction_type,
        ];
    }
}