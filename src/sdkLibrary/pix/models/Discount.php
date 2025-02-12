<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The Discount class represents the details of a discount
 * applicable to a transaction.
 *
 * It includes fields for the modality of the discount,
 * the percentage value, and a list of fixed date discounts that
 * may apply.
 */
class Discount
{
    private ?int $modality;
    private ?string $value_percentage;
    private array $fixed_date_discounts;

    public function __construct(
        ?int $modality = null,
        ?string $value_percentage = null,
        array $fixed_date_discounts = []
    ) {
        $this->modality = $modality;
        $this->value_percentage = $value_percentage;
        $this->fixed_date_discounts = $fixed_date_discounts;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['modalidade'] ?? null,
            $data['valorPerc'] ?? null,
            array_map(fn($discount) => FixedDateDiscount::fromJson($discount), $data['descontoDataFixa'] ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            "modalidade" => $this->modality,
            "valorPerc" => $this->value_percentage,
            "descontoDataFixa" => array_map(fn($discount) => $discount->toArray(), $this->fixed_date_discounts)
        ];
    }
}