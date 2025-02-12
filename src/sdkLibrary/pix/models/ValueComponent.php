<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The ValueComponent class represents various monetary
 * components related to a financial transaction, including the
 * original amount, change (troco), discounts, and additional
 * charges such as interest (juros) and penalties (multa).
 */
class ValueComponent
{
    private ?ComponentValue $original;
    private ?ComponentValue $change;
    private ?ComponentValue $discount_amount;
    private ?ComponentValue $direct_discount;
    private ?ComponentValue $interest;
    private ?ComponentValue $penalty;

    public function __construct(
        ?ComponentValue $original = null,
        ?ComponentValue $change = null,
        ?ComponentValue $discount_amount = null,
        ?ComponentValue $direct_discount = null,
        ?ComponentValue $interest = null,
        ?ComponentValue $penalty = null
    ) {
        $this->original = $original;
        $this->change = $change;
        $this->discount_amount = $discount_amount;
        $this->direct_discount = $direct_discount;
        $this->interest = $interest;
        $this->penalty = $penalty;
    }

    public static function fromJson(array $data): self
    {
        return new self(
            isset($data["original"]) ? ComponentValue::fromJson($data["original"]) : null,
            isset($data["troco"]) ? ComponentValue::fromJson($data["troco"]) : null,
            isset($data["abatimento"]) ? ComponentValue::fromJson($data["abatimento"]) : null,
            isset($data["desconto"]) ? ComponentValue::fromJson($data["desconto"]) : null,
            isset($data["juros"]) ? ComponentValue::fromJson($data["juros"]) : null,
            isset($data["multa"]) ? ComponentValue::fromJson($data["multa"]) : null
        );
    }

    public function toArray(): array
    {
        return [
            "original" => $this->original?->toArray(),
            "troco" => $this->change?->toArray(),
            "abatimento" => $this->discount_amount?->toArray(),
            "desconto" => $this->direct_discount?->toArray(),
            "juros" => $this->interest?->toArray(),
            "multa" => $this->penalty?->toArray()
        ];
    }
}