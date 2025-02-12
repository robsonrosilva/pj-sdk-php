<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use Inter\Sdk\sdkLibrary\pix\enums\ImmediateBillingType;

/**
 * The RetrieveLocationFilter class is used to filter location
 * requests based on certain criteria, including the presence of
 * transaction ID and the type of immediate billing.
 */
class RetrieveLocationFilter
{
    private ?bool $tx_id_present;
    private ?ImmediateBillingType $billing_type;

    public function __construct(
        ?bool $tx_id_present = null,
        ?ImmediateBillingType $billing_type = null
    ) {
        $this->tx_id_present = $tx_id_present;
        $this->billing_type = $billing_type;
    }

    public function getTxIdPresent(): ?bool
    {
        return $this->tx_id_present;
    }

    public function setTxIdPresent(?bool $tx_id_present): void
    {
        $this->tx_id_present = $tx_id_present;
    }

    public function getBillingType(): ?ImmediateBillingType
    {
        return $this->billing_type;
    }

    public function setBillingType(?ImmediateBillingType $billing_type): void
    {
        $this->billing_type = $billing_type;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['txIdPresente'] ?? null,
            isset($data['tipoCob']) ? ImmediateBillingType::fromString($data['tipoCob']) : null
        );
    }

    public function toArray(): array
    {
        return [
            "txIdPresente" => $this->tx_id_present,
            "tipoCob" => $this->billing_type?->value
        ];
    }
}