<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use JsonException;

/**
 * The DueBillingBatch class represents a batch of due billing
 * transactions.
 *
 * It includes fields for the batch ID, a description of the batch,
 * the creation date, and a list of individual due billing entities
 * associated with this batch.
 */
class DueBillingBatch
{
    private ?string $id;
    private ?string $description;
    private ?string $creation_date;
    private array $due_billing_entities;

    public function __construct(
        ?string $id = null,
        ?string $description = null,
        ?string $creation_date = null,
        array $due_billing_entities = []
    ) {
        $this->id = $id;
        $this->description = $description;
        $this->creation_date = $creation_date;
        $this->due_billing_entities = $due_billing_entities;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['descricao'] ?? null,
            $data['criacao'] ?? null,
            array_map(fn($entity) => DueBillingEntity::fromJson($entity), $data['cobsv'] ?? [])
        );
    }

    /**
     * @throws JsonException
     */
    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "descricao" => $this->description,
            "criacao" => $this->creation_date,
            "cobsv" => array_map(fn($entity) => $entity->toArray(), $this->due_billing_entities)
        ];
    }
}