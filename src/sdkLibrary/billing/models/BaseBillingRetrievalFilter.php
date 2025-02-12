<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

use Inter\Sdk\sdkLibrary\billing\enums\BillingDateType;
use Inter\Sdk\sdkLibrary\billing\enums\BillingSituation;
use Inter\Sdk\sdkLibrary\billing\enums\BillingType;
use JsonException;

/**
 * The BaseBillingRetrievalFilter class represents the filter criteria
 * for retrieving billing information, including various parameters.
 */
class BaseBillingRetrievalFilter
{
    private ?BillingDateType $filter_date_by;
    private ?BillingSituation $situation;
    private ?string $payer;
    private ?string $payer_cpf_cnpj;
    private ?string $your_number;
    private ?BillingType $billing_type;

    public function getFilterDateBy(): ?BillingDateType
    {
        return $this->filter_date_by;
    }

    public function getSituation(): ?BillingSituation
    {
        return $this->situation;
    }

    public function getPayer(): ?string
    {
        return $this->payer;
    }

    public function getPayerCpfCnpj(): ?string
    {
        return $this->payer_cpf_cnpj;
    }

    public function getYourNumber(): ?string
    {
        return $this->your_number;
    }

    public function getBillingType(): ?BillingType
    {
        return $this->billing_type;
    }

    public function __construct(
        ?BillingDateType $filter_date_by = null,
        ?BillingSituation $situation = null,
        ?string $payer = null,
        ?string $payer_cpf_cnpj = null,
        ?string $your_number = null,
        ?BillingType $billing_type = null
    ) {
        $this->filter_date_by = $filter_date_by;
        $this->situation = $situation;
        $this->payer = $payer;
        $this->payer_cpf_cnpj = $payer_cpf_cnpj;
        $this->your_number = $your_number;
        $this->billing_type = $billing_type;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            isset($json['filtrarDataPor']) ? BillingDateType::fromString($json['filtrarDataPor']) : null,
            isset($json['situacao']) ? BillingSituation::fromString($json['situacao']) : null,
            $json['pessoaPagadora'] ?? null,
            $json['cpfCnpjPessoaPagadora'] ?? null,
            $json['seuNumero'] ?? null,
            isset($json['tipoCobranca']) ? BillingType::fromString($json['tipoCobranca']) : null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "filtrarDataPor" => $this->filter_date_by?->value,
            "situacao" => $this->situation?->value,
            "pessoaPagadora" => $this->payer,
            "cpfCnpjPessoaPagadora" => $this->payer_cpf_cnpj,
            "seuNumero" => $this->your_number,
            "tipoCobranca" => $this->billing_type?->value
        ];
    }
}