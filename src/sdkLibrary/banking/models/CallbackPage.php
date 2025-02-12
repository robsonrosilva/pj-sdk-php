<?php
namespace Inter\Sdk\sdkLibrary\banking\models;
use JsonException;

/**
 * The CallbackPage class represents a paginated response for callback data,
 * including pagination information and the list of responses.
 */
class CallbackPage
{
    private ?int $total_pages;
    private ?int $total_elements;
    private ?bool $last_page;
    private ?bool $first_page;
    private ?int $page_size;
    private ?int $number_of_elements;
    private ?array $data;

    public function __construct(
        ?int $total_pages = null,
        ?int $total_elements = null,
        ?bool $last_page = null,
        ?bool $first_page = null,
        ?int $page_size = null,
        ?int $number_of_elements = null,
        ?array $data = null
    ) {
        $this->total_pages = $total_pages;
        $this->total_elements = $total_elements;
        $this->last_page = $last_page;
        $this->first_page = $first_page;
        $this->page_size = $page_size;
        $this->number_of_elements = $number_of_elements;
        $this->data = $data;
    }
    public function getNumberOfPages(): int
    {
        return $this->total_pages ?? 0;
    }
    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['totalPaginas'] ?? null,
            $json['totalElementos'] ?? null,
            $json['ultimaPagina'] ?? null,
            $json['primeiraPagina'] ?? null,
            $json['tamanhoPagina'] ?? null,
            $json['numeroDeElementos'] ?? null,
            isset($json['data']) ? array_map(
                fn($item) => RetrieveCallbackResponse::fromJson($item),
                $json['data']
            ) : null
        );
    }
    /**
     * @throws JsonException
     */
    public function toArray(): array
    {
        return [
            "totalPaginas" => $this->total_pages,
            "totalElementos" => $this->total_elements,
            "ultimaPagina" => $this->last_page,
            "primeiraPagina" => $this->first_page,
            "tamanhoPagina" => $this->page_size,
            "numeroDeElementos" => $this->number_of_elements,
            "data" => $this->data ? array_map(
                fn($response) => $response->toArray(),
                $this->data
            ) : [],
        ];

    }
    // Getters e Setters (opcional, se necessário)
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
    public function getData(): ?array
    {
        return $this->data;
    }
    public function setData(?array $data): void
    {
        $this->data = $data;
    }
}