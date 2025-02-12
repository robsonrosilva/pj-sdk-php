<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The PixPayload class represents a container for a list of
 * transaction items related to PIX (Payment Instantâneo).
 * It holds multiple item payloads.
 */
class PixPayload
{
    private array $pix_items;

    public function __construct(array $pix_items = [])
    {
        $this->pix_items = $pix_items;
    }

    public static function fromJson(array $data): self
    {
        return new self(
            array_map(fn($item) => ItemPayload::fromJson($item), $data ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            "pix" => array_map(fn($item) => $item->toArray(), $this->pix_items)
        ];
    }
}