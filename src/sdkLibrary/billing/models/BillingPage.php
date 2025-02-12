<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

/**
 * The BillingPage class represents a paginated response containing a
 * collection of retrieved billings.
 *
 * It includes details about the total number of pages, total elements,
 * and information indicating whether it is the first or last page. Additionally, it
 * holds a list of retrieved billing information. This structure supports pagination
 * in the billing retrieval processes.
 */
class BillingPage
{
    private ?int $total_pages;
    private ?int $total_elements;
    private ?bool $last_page;
    private ?bool $first_page;
    private ?int $page_size;
    private ?int $number_of_elements;
    private array $billings;

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

    public function getBillings(): array
    {
        return $this->billings;
    }

    public function setBillings(array $billings): void
    {
        $this->billings = $billings;
    } // List of RetrievedBilling objects

    public function __construct(
        ?int $total_pages = null,
        ?int $total_elements = null,
        ?bool $last_page = null,
        ?bool $first_page = null,
        ?int $page_size = null,
        ?int $number_of_elements = null,
        ?array $billings = null
    ) {
        $this->total_pages = $total_pages;
        $this->total_elements = $total_elements;
        $this->last_page = $last_page;
        $this->first_page = $first_page;
        $this->page_size = $page_size;
        $this->number_of_elements = $number_of_elements;
        $this->billings = $billings;
    }

    public function getPageNumber(): int
    {
        // Returns the quantity of pages available.
        return $this->total_pages ?? 0;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            $json['totalPaginas'] ?? null,
            $json['totalElementos'] ?? null,
            $json['ultimaPagina'] ?? null,
            $json['primeiraPagina'] ?? null,
            $json['tamanhoPagina'] ?? null,
            $json['numeroDeElementos'] ?? null,
            array_map(fn($item) => RetrievedBilling::fromJson($item), $json['cobrancas'] ?? [])
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "totalPaginas" => $this->total_pages,
            "totalElementos" => $this->total_elements,
            "ultimaPagina" => $this->last_page,
            "primeiraPagina" => $this->first_page,
            "tamanhoPagina" => $this->page_size,
            "numeroDeElementos" => $this->number_of_elements,
            "cobrancas" => array_map(fn($billing) => $billing->toArray(), $this->billings)
        ];
    }
}