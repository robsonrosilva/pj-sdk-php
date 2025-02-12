<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The PixCallbackPage class represents a paginated response
 * of callbacks, containing information about the total number
 * of pages, total elements, flags indicating if it's the
 * first or last page, page size and number of elements in the
 * current page, along with the actual list of callback responses.
 *
 * This structure is essential for managing and navigating
 * through large sets of callback data effectively.
 */
class PixCallbackPage
{
    private ?int $total_pages;
    private ?int $total_elements;
    private ?bool $last_page;
    private ?bool $first_page;
    private ?int $page_size;
    private ?int $number_of_elements;
    private array $data;

    public function __construct(
        ?int $total_pages = null,
        ?int $total_elements = null,
        ?bool $last_page = null,
        ?bool $first_page = null,
        ?int $page_size = null,
        ?int $number_of_elements = null,
        array $data = []
    ) {
        $this->total_pages = $total_pages;
        $this->total_elements = $total_elements;
        $this->last_page = $last_page;
        $this->first_page = $first_page;
        $this->page_size = $page_size;
        $this->number_of_elements = $number_of_elements;
        $this->data = $data;
    }

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

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getPageNumber(): int
    {
        /**
         * Returns the total number of pages for the callback response.
         *
         * Returns:
         *     int: The total number of pages or 0 if no pages are specified.
         */
        return $this->total_pages ?? 0;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['totalPaginas'] ?? null,
            $data['totalElementos'] ?? null,
            $data['ultimaPagina'] ?? null,
            $data['primeiraPagina'] ?? null,
            $data['tamanhoPagina'] ?? null,
            $data['numeroDeElementos'] ?? null,
                isset($data['data']) ? array_map(
                    fn($item) => RetrieveCallbackResponse::fromJson($item),
                    $data['data']
                ) : null

        );
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
            "data" => array_map(fn($item) => $item->toArray(), $this->data)
        ];
    }
}