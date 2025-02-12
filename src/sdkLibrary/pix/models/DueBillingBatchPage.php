<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use JsonException;

/**
 * The DueBillingBatchPage class represents a paginated
 * response for due billing batches.
 *
 * It includes fields for request parameters and
 * a list of batches, allowing for easy access to pagination
 * information and additional dynamic fields.
 */
class DueBillingBatchPage
{
    private ?Parameters $parameters;
    private array $batches;

    public function __construct(
        ?Parameters $parameters,
        array $batches = []
    ) {
        $this->parameters = $parameters;
        $this->batches = $batches;
    }

    public function getParameters(): ?Parameters
    {
        return $this->parameters;
    }

    public function setParameters(?Parameters $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getBatches(): array
    {
        return $this->batches;
    }

    public function setBatches(array $batches): void
    {
        $this->batches = $batches;
    }

    public function getTotalPages(): int
    {
        /**
         * Returns the total number of pages for the billing due response.
         *
         * Returns:
         *    int: The total number of pages, or 0 if parameters or pagination
         *    details are not available.
         */
        if ($this->parameters === null || $this->parameters->getPagination() === null) {
            return 0;
        }
        return $this->parameters->getPagination()->getTotalPages();
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            isset($data['parametros']) ? Parameters::fromJson($data['parametros']) : null,
            array_map(fn($batch) => DueBillingBatch::fromJson($batch), $data['lotes'] ?? [])
        );
    }

    /**
     * @throws JsonException
     */
    public function toArray(): array
    {
        return [
            "parametros" => $this->parameters?->toArray(),
            "lotes" => array_map(fn($batch) => $batch->toArray(), $this->batches)
        ];
    }
}