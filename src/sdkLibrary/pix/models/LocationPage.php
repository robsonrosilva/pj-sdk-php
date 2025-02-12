<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use DateMalformedStringException;

/**
 * The LocationPage class represents a paginated response
 * containing a list of locations. It includes parameters for
 * pagination, a list of locations, and supports additional
 * custom fields through a map.
 */
class LocationPage
{
    private ?Parameters $parameters;
    private array $locations;

    public function __construct(
        ?Parameters $parameters = null,
        array $locations = []
    ) {
        $this->parameters = $parameters;
        $this->locations = $locations;
    }

    public function getParameters(): ?\Inter\Sdk\sdkLibrary\pix\models\Parameters
    {
        return $this->parameters;
    }

    public function setParameters(?\Inter\Sdk\sdkLibrary\pix\models\Parameters $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getLocations(): array
    {
        return $this->locations;
    }

    public function setLocations(array $locations): void
    {
        $this->locations = $locations;
    }

    public function getTotalPages(): int
    {
        /**
         * Returns the total number of pages for the locations response.
         *
         * Returns:
         *     int: The total number of pages, or 0 if parameters or pagination
         *     details are not available.
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
            array_map( fn($loc) => Location::fromJson($loc), $data['loc'] ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            "parametros" => $this->parameters?->toArray(),
            "loc" => array_map(fn($loc) => $loc->toArray(), $this->locations)
        ];
    }
}