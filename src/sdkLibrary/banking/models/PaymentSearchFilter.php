<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use Inter\Sdk\sdkLibrary\banking\enums\PaymentDateType;
use JsonException;

/**
 * The PaymentSearchFilter class represents the filter criteria for searching payments,
 * including barcode, transaction code, and date filtering type.
 */
class PaymentSearchFilter
{
    private ?string $barcode;
    private ?string $transaction_code;
    private ?PaymentDateType $filter_date_by;

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getTransactionCode(): ?string
    {
        return $this->transaction_code;
    }

    public function setTransactionCode(?string $transaction_code): void
    {
        $this->transaction_code = $transaction_code;
    }

    public function getFilterDateBy(): ?PaymentDateType
    {
        return $this->filter_date_by;
    }

    public function setFilterDateBy(?PaymentDateType $filter_date_by): void
    {
        $this->filter_date_by = $filter_date_by;
    }

    public function __construct(?string $barcode = null, ?string $transaction_code = null, ?PaymentDateType $filter_date_by = null)
    {
        $this->barcode = $barcode;
        $this->transaction_code = $transaction_code;
        $this->filter_date_by = $filter_date_by;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['codBarraLinhaDigitavel'] ?? null,
            $json['codigoTransacao'] ?? null,
            isset($json['filtrarDataPor']) ? PaymentDateType::fromString($json['filtrarDataPor']) : null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode([
            "codBarraLinhaDigitavel" => $this->barcode,
            "codigoTransacao" => $this->transaction_code,
            "filtrarDataPor" => $this->filter_date_by ? $this->filter_date_by : null
        ], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "codBarraLinhaDigitavel" => $this->barcode,
            "codigoTransacao" => $this->transaction_code,
            "filtrarDataPor" => $this->filter_date_by ? $this->filter_date_by : null
        ];
    }
}