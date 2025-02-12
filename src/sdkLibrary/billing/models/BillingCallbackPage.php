<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

use JsonException;

/**
 * The BillingCallbackPage class represents a paginated response
 * containing billing callback data.
 */
class BillingCallbackPage
{
    private ?int $total_pages;
    private ?int $total_elements;
    private ?bool $last_page;
    private ?bool $first_page;
    private ?int $page_size;
    private ?int $number_of_elements;
    private array $callbacks;

    public function getTotalPages(): ?int
    {
        return $this->total_pages;
    }

    public function setTotalPages(?int $total_pages): void
    {
        $this->total_pages = $total_pages;
    }

    public function getTotalElements(): ?int
    {
        return $this->total_elements;
    }

    public function setTotalElements(?int $total_elements): void
    {
        $this->total_elements = $total_elements;
    }

    public function getLastPage(): ?bool
    {
        return $this->last_page;
    }

    public function setLastPage(?bool $last_page): void
    {
        $this->last_page = $last_page;
    }

    public function getFirstPage(): ?bool
    {
        return $this->first_page;
    }

    public function setFirstPage(?bool $first_page): void
    {
        $this->first_page = $first_page;
    }

    public function getPageSize(): ?int
    {
        return $this->page_size;
    }

    public function setPageSize(?int $page_size): void
    {
        $this->page_size = $page_size;
    }

    public function getNumberOfElements(): ?int
    {
        return $this->number_of_elements;
    }

    public function setNumberOfElements(?int $number_of_elements): void
    {
        $this->number_of_elements = $number_of_elements;
    }

    public function getCallbacks(): array
    {
        return $this->callbacks;
    }

    public function setCallbacks(array $callbacks): void
    {
        $this->callbacks = $callbacks;
    } // List of BillingRetrieveCallbackResponse objects

    public function __construct(
        ?int $total_pages = null,
        ?int $total_elements = null,
        ?bool $last_page = null,
        ?bool $first_page = null,
        ?int $page_size = null,
        ?int $number_of_elements = null,
        ?array $callbacks = null
    ) {
        $this->total_pages = $total_pages;
        $this->total_elements = $total_elements;
        $this->last_page = $last_page;
        $this->first_page = $first_page;
        $this->page_size = $page_size;
        $this->number_of_elements = $number_of_elements;
        $this->callbacks = $callbacks;
    }

    public function getPageNumber(): int
    {
        // Gets the current page number based on total pages.
        return $this->total_pages ?? 0;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['totalPaginas'] ?? null,
            $json['totalElementos'] ?? null,
            $json['ultimaPagina'] ?? null,
            $json['primeiraPagina'] ?? null,
            $json['size'] ?? null,
            $json['numberOfElements'] ?? null,
            array_map(fn($item) => BillingRetrieveCallbackResponse::fromJson($item), $json['data'] ?? [])
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
            "totalPaginas" => $this->total_pages,
            "totalElementos" => $this->total_elements,
            "ultimaPagina" => $this->last_page,
            "primeiraPagina" => $this->first_page,
            "size" => $this->page_size,
            "numberOfElements" => $this->number_of_elements,
            "data" => array_map(fn($callback) => $callback->toArray(), $this->callbacks)
        ];
    }
}