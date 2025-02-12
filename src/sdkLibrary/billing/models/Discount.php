<?php

namespace Inter\Sdk\sdkLibrary\billing\models;
use Inter\Sdk\sdkLibrary\banking\enums\DiscountCode;

/**
 * The Discount class represents a discount applied to a specific
 * transaction.
 *
 * It includes details such as the discount code, the number of days
 * for which it is valid, the percentage rate of the discount, and the total
 * amount of the discount.
 */
class Discount
{
    private ?DiscountCode $code;
    private ?int $number_of_days;
    private ?float $rate;
    private ?float $value;

    public function __construct(
        ?DiscountCode $code = null,
        ?int $number_of_days = null,
        ?float $rate = null,
        ?float $value = null
    ) {
        $this->code = $code;
        $this->number_of_days = $number_of_days;
        $this->rate = $rate;
        $this->value = $value;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            isset($json['codigo']) ? DiscountCode::fromString($json['codigo']) : null,
            $json['quantidadeDias'] ?? null,
            isset($json['taxa']) ? ($json['taxa']) : null,
            isset($json['valor']) ? ($json['valor']) : null
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "codigo" => $this->code?->value,
            "quantidadeDias" => $this->number_of_days,
            "taxa" => $this->rate ? floatval($this->rate) : null,
            "valor" => $this->value ? floatval($this->value) : null
        ];
    }
}