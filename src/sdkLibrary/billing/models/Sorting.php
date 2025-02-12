<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

use Inter\Sdk\sdkLibrary\billing\enums\OrderBy;
use Inter\Sdk\sdkLibrary\billing\enums\OrderType;

/**
 * The Sorting class represents the sorting criteria used
 * for retrieving billing data.
 *
 * It includes fields for specifying the order by which
 * the results should be sorted, as well as the type of sorting
 * (ascending or descending). This structure is essential for
 * organizing the output of billing retrieval processes according
 * to user or system preferences.
 */
class Sorting
{
    private ?OrderBy $order_by;
    private ?OrderType $sort_type;

    public function getOrderBy(): ?OrderBy
    {
        return $this->order_by;
    }

    public function setOrderBy(?OrderBy $order_by): void
    {
        $this->order_by = $order_by;
    }

    public function getSortType(): ?OrderType
    {
        return $this->sort_type;
    }

    public function setSortType(?OrderType $sort_type): void
    {
        $this->sort_type = $sort_type;
    }

    public function __construct(
        ?OrderBy $order_by = null,
        ?OrderType $sort_type = null
    ) {
        $this->order_by = $order_by;
        $this->sort_type = $sort_type;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            isset($json['ordenarPor']) ? OrderBy::fromString($json['ordenarPor']) : null,
            isset($json['tipoOrdenacao']) ? OrderType::fromString($json['tipoOrdenacao']) : null
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "ordenarPor" => $this->order_by?->value,
            "tipoOrdenacao" => $this->sort_type?->value
        ];
    }
}