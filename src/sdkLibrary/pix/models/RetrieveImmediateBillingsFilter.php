<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use Inter\Sdk\sdkLibrary\pix\enums\BillingStatus;

/**
 * The RetrieveImmediateBillingsFilter class is used to filter
 * immediate billing records based on various criteria. It includes
 * fields for CPF, CNPJ, presence of location, and billing status.
 * Additionally, it supports custom fields through a map for
 * additional attributes.
 */
class RetrieveImmediateBillingsFilter
{
    private ?string $cpf;
    private ?string $cnpj;
    private ?bool $location_present;
    private ?BillingStatus $status;

    public function __construct(
        ?string $cpf = null,
        ?string $cnpj = null,
        ?bool $location_present = null,
        ?BillingStatus $status = null
    ) {
        $this->cpf = $cpf;
        $this->cnpj = $cnpj;
        $this->location_present = $location_present;
        $this->status = $status;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(?string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function setCnpj(?string $cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    public function getLocationPresent(): ?bool
    {
        return $this->location_present;
    }

    public function setLocationPresent(?bool $location_present): void
    {
        $this->location_present = $location_present;
    }

    public function getStatus(): ?BillingStatus
    {
        return $this->status;
    }

    public function setStatus(?BillingStatus $status): void
    {
        $this->status = $status;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['cpf'] ?? null,
            $data['cnpj'] ?? null,
            $data['locationPresente'] ?? null,
            isset($data['status']) ? BillingStatus::fromString($data['status']) : null
        );
    }

    public function toArray(): array
    {
        return [
            "cpf" => $this->cpf,
            "cnpj" => $this->cnpj,
            "locationPresente" => $this->location_present,
            "status" => $this->status?->value
        ];
    }
}