<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The DueBillingEntity class represents a single billing
 * transaction within a due billing batch.
 *
 * It includes fields for the transaction ID (txid), the
 * status of the transaction, any associated problem details, and
 * the creation date of the transaction.
 */
class DueBillingEntity
{
    private ?string $txid;
    private ?string $status;
    private ?Problem $problem;
    private ?string $creation_date;

    public function __construct(
        ?string $txid = null,
        ?string $status = null,
        ?Problem $problem = null,
        ?string $creation_date = null
    ) {
        $this->txid = $txid;
        $this->status = $status;
        $this->problem = $problem;
        $this->creation_date = $creation_date;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['txid'] ?? null,
            $data['status'] ?? null,
            isset($data['problema']) ? Problem::fromJson($data['problema']) : null,
            $data['criacao'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "txid" => $this->txid,
            "status" => $this->status,
            "problema" => $this->problem?->toArray(),
            "criacao" => $this->creation_date
        ];
    }
}