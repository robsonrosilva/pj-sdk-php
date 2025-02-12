<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The BillingPage class represents a paginated response
 * containing detailed billing information, specifically for
 * immediate PIX transactions.
 *
 * It includes parameters for pagination, a list of
 * billing entries, and supports additional custom fields through
 * a map. This structure is essential for organizing responses and
 * providing a user-friendly way to navigate through billing data.
 */
class BillingPage
{
    private ?Parameters $parameters;
    private array $billings;

    public function __construct(
        ?Parameters $parameters = null,
        array $billings = []
    ) {
        $this->parameters = $parameters;
        $this->billings = $billings;
    }

    public function getParameters(): ?Parameters
    {
        return $this->parameters;
    }

    public function setParameters(?Parameters $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getBillings(): array
    {
        return $this->billings;
    }

    public function setBillings(array $billings): void
    {
        $this->billings = $billings;
    }

    public function getTotalPages(): int
    {
        /**
         * Returns the total number of pages for the billing response.
         *
         * Returns:
         *     int: The total number of pages, or 0 if parameters or pagination are not set.
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
            array_map(fn($item) => DetailedImmediatePixBilling::fromJson($item), $data['cobs'] ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            "parametros" => $this->parameters?->toArray(),
            "cobs" => array_map(fn($billing) => $billing->toArray(), $this->billings)
        ];
    }
}