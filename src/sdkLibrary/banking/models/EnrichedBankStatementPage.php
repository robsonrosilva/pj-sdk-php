<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The EnrichedBankStatementPage class represents a paginated response for enriched bank statements,
 * including pagination details and a list of transactions.
 */
class EnrichedBankStatementPage
{
    private ?int $total_pages;
    private ?int $total_elements;
    private ?bool $last_page;
    private ?bool $first_page;
    private ?int $page_size;
    private ?int $number_of_elements;
    private ?array $transactions;

    public function getTotalPages(): ?int
    {
        return $this->total_pages;
    }

    public function getTotalElements(): ?int
    {
        return $this->total_elements;
    }

    public function getLastPage(): ?bool
    {
        return $this->last_page;
    }

    public function getFirstPage(): ?bool
    {
        return $this->first_page;
    }

    public function getPageSize(): ?int
    {
        return $this->page_size;
    }

    public function getNumberOfElements(): ?int
    {
        return $this->number_of_elements;
    }

    public function getTransactions(): ?array
    {
        return $this->transactions;
    }

    public function __construct(
        ?int $total_pages = null,
        ?int $total_elements = null,
        ?bool $last_page = null,
        ?bool $first_page = null,
        ?int $page_size = null,
        ?int $number_of_elements = null,
        ?array $transactions = null
    ) {
        $this->total_pages = $total_pages;
        $this->total_elements = $total_elements;
        $this->last_page = $last_page;
        $this->first_page = $first_page;
        $this->page_size = $page_size;
        $this->number_of_elements = $number_of_elements;
        $this->transactions = $transactions;
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
            isset($json['transacoes']) ? array_map(
                fn($item) => EnrichedTransaction::fromJson($item),
                $json['transacoes']
            ) : null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "totalPaginas" => $this->total_pages,
            "totalElementos" => $this->total_elements,
            "ultimaPagina" => $this->last_page,
            "primeiraPagina" => $this->first_page,
            "tamanhoPagina" => $this->page_size,
            "numeroDeElementos" => $this->number_of_elements,
            "transacoes" => $this->transactions ? array_map(
                fn($transaction) => $transaction->toJson(),
                $this->transactions
            ) : [],
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
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
            "transacoes" => $this->transactions ? array_map(
                fn($transaction) => $transaction->toArray(),
                $this->transactions
            ) : [],
        ];
    }
}