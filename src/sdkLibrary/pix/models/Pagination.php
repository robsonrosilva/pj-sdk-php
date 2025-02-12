<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The Pagination class represents the pagination details
 * for a collection of items, including the current page, items
 * per page, total number of pages, and total number of items.
 * It also supports additional custom fields via a map of
 * additional attributes.
 */
class Pagination
{
    private int $current_page;
    private int $items_per_page;
    private int $total_pages;
    private int $total_items;

    public function getCurrentPage(): int
    {
        return $this->current_page;
    }

    public function getItemsPerPage(): int
    {
        return $this->items_per_page;
    }

    public function getTotalPages(): int
    {
        return $this->total_pages;
    }

    public function getTotalItems(): int
    {
        return $this->total_items;
    }

    public function __construct(
        int $current_page = 0,
        int $items_per_page = 0,
        int $total_pages = 0,
        int $total_items = 0
    ) {
        $this->current_page = $current_page;
        $this->items_per_page = $items_per_page;
        $this->total_pages = $total_pages;
        $this->total_items = $total_items;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['paginaAtual'] ?? 0,
            $data['itensPorPagina'] ?? 0,
            $data['quantidadeDePaginas'] ?? 0,
            $data['quantidadeTotalDeItens'] ?? 0
        );
    }

    public function toArray(): array
    {
        return [
            "paginaAtual" => $this->current_page,
            "itensPorPagina" => $this->items_per_page,
            "quantidadeDePaginas" => $this->total_pages,
            "quantidadeTotalDeItens" => $this->total_items
        ];
    }
}