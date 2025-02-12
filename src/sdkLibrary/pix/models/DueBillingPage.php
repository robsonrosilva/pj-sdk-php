<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use DateMalformedStringException;

/**
 * The DueBillingPage class represents a paginated response
 * containing detailed billing information that is due for payment.
 *
 * It includes parameters for pagination, a list of detailed
 * due billings, and supports additional custom fields through a map.
 */
class DueBillingPage
{
    private ?Parameters $parameters;
    private array $due_billings;

    public function getParameters(): ?Parameters
    {
        return $this->parameters;
    }

    public function setParameters(?Parameters $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getDueBillings(): array
    {
        return $this->due_billings;
    }

    public function setDueBillings(array $due_billings): void
    {
        $this->due_billings = $due_billings;
    }

    public function __construct(
        ?Parameters $parameters = null,
        array $due_billings = []
    ) {
        $this->parameters = $parameters;
        $this->due_billings = $due_billings;
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

    /**
     * @throws DateMalformedStringException
     */
    public static function fromJson(mixed $data): self
    {
        return new self(
            isset($data['parametros']) ? Parameters::fromJson($data['parametros']) : null,
            array_map( fn($billing) => DetailedDuePixBilling::fromJson($billing), $data['cobs'] ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            "parametros" => $this->parameters?->toArray(),
            "cobs" => array_map(fn($billing) => $billing->toArray(), $this->due_billings)
        ];
    }
}