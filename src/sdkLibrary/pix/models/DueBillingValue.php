<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The DueBillingValue class represents the structure of a billing
 * value in a transaction.
 *
 * It includes fields for the original value, fines (Fine),
 * fees (Fees), reductions (Reduction), and discounts (Discount).
 * This structure allows for a comprehensive representation of all
 * financial aspects related to the billing transaction.
 */
class DueBillingValue
{
    private ?string $original_value;
    private ?Fine $penalty;
    private ?Fees $interest;
    private ?Reduction $reduction;
    private ?Discount $discount;

    public function __construct(
        ?string $original_value = null,
        ?Fine $penalty = null,
        ?Fees $interest = null,
        ?Reduction $reduction = null,
        ?Discount $discount = null
    ) {
        $this->original_value = $original_value;
        $this->penalty = $penalty;
        $this->interest = $interest;
        $this->reduction = $reduction;
        $this->discount = $discount;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['original'] ?? null,
            isset($data['multa']) ? Fine::fromJson($data['multa']) : null,
            isset($data['juros']) ? Fees::fromJson($data['juros']) : null,
            isset($data['abatimento']) ? Reduction::fromJson($data['abatimento']) : null,
            isset($data['desconto']) ? Discount::fromJson($data['desconto']) : null
        );
    }

    public function toArray(): array
    {
        return [
            "original" => $this->original_value,
            "multa" => $this->penalty?->toArray(),
            "juros" => $this->interest?->toArray(),
            "abatimento" => $this->reduction?->toArray(),
            "desconto" => $this->discount?->toArray()
        ];
    }
}