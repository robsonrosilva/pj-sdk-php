<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use DateMalformedStringException;

/**
 * The IncludeDueBillingBatchRequest class represents a request
 * to include a batch of due billings.
 *
 * It consists of a description for the batch and a list
 * of due billings to be included in the request.
 */
class IncludeDueBillingBatchRequest
{
    private ?string $description;
    private array $due_billings;

    public function __construct(
        ?string $description = null,
        array $due_billings = []
    ) {
        $this->description = $description;
        $this->due_billings = $due_billings;
    }

    /**
     * @throws DateMalformedStringException
     */
    public static function fromJson(array $data): self
    {
        return new self(
            $data['descricao'] ?? null,
            array_map(fn($billing) => DueBilling::fromJson($billing), $data['cobsv'] ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            "descricao" => $this->description,
            "cobsv" => array_map(fn($billing) => $billing->toArray(), $this->due_billings)
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}