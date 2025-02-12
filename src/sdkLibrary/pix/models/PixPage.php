<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use DateMalformedStringException;

/**
 * The PixPage class represents a paginated response containing
 * a list of PIX transactions. It includes parameters for pagination,
 * and a list of PIX entries.
 */
class PixPage
{
    private ?Parameters $parameters;
    private array $pix_list;

    public function __construct(
        ?Parameters $parameters = null,
        array $pix_list = []
    ) {
        $this->parameters = $parameters;
        $this->pix_list = $pix_list;
    }

    public function getParameters(): ?Parameters
    {
        return $this->parameters;
    }

    public function setParameters(?Parameters $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getPixList(): array
    {
        return $this->pix_list;
    }

    public function setPixList(array $pix_list): void
    {
        $this->pix_list = $pix_list;
    }

    public function getTotalPages(): int
    {
        /**
         * Returns the total number of pages for the PIX response.
         *
         * Returns:
         *     int: The total number of pages, or 0 if parameters or pagination
         *     details are not available.
         */
        return ($this->parameters !== null && $this->parameters->getPagination() !== null)
            ? $this->parameters->getPagination()->getTotalPages()
            : 0;
    }

    /**
     * @throws DateMalformedStringException
     */
    public static function fromJson(mixed $data): self
    {
        return new self(
            isset($data['parametros']) ? Parameters::fromJson($data['parametros']) : null,
            array_map( fn($pix) => Pix::fromJson($pix), $data['pix'] ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            "parametros" => $this->parameters?->toArray(),
            "pix" => array_map(fn($pix) => $pix->toArray(), $this->pix_list)
        ];
    }
}