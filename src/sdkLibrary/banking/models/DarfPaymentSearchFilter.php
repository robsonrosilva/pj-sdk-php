<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use Inter\Sdk\sdkLibrary\banking\enums\DarfPaymentDateType;
use JsonException;

/**
 * The DarfPaymentSearchFilter class represents the search filter for DARF payments,
 * including request code, revenue code, and date filtering options.
 */
class DarfPaymentSearchFilter
{
    private ?string $request_code;
    private ?string $revenue_code;

    public function getRequestCode(): ?string
    {
        return $this->request_code;
    }

    public function setRequestCode(?string $request_code): void
    {
        $this->request_code = $request_code;
    }

    public function getRevenueCode(): ?string
    {
        return $this->revenue_code;
    }

    public function setRevenueCode(?string $revenue_code): void
    {
        $this->revenue_code = $revenue_code;
    }

    public function getFilterDateBy(): ?DarfPaymentDateType
    {
        return $this->filter_date_by;
    }

    public function setFilterDateBy(?DarfPaymentDateType $filter_date_by): void
    {
        $this->filter_date_by = $filter_date_by;
    }
    private ?DarfPaymentDateType $filter_date_by;

    public function __construct(
        ?string $request_code = null,
        ?string $revenue_code = null,
        ?DarfPaymentDateType $filter_date_by = null
    ) {
        $this->request_code = $request_code;
        $this->revenue_code = $revenue_code;
        $this->filter_date_by = $filter_date_by;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['codigoSolicitacao'] ?? null,
            $json['codigoReceita'] ?? null,
            isset($json['filtrarDataPor']) ? DarfPaymentDateType::fromString($json['filtrarDataPor']) : null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "codigoSolicitacao" => $this->request_code,
            "codigoReceita" => $this->revenue_code,
            "filtrarDataPor" => $this->filter_date_by?->value,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "codigoSolicitacao" => $this->request_code,
            "codigoReceita" => $this->revenue_code,
            "filtrarDataPor" => $this->filter_date_by?->value,
        ];
    }
}