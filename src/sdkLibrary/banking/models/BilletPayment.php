<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The BilletPayment class represents the details of a boleto payment,
 * including payment amount, dates, and beneficiary information.
 */
class BilletPayment
{
    private ?string $barcode;
    private ?float $amount_to_pay;
    private ?string $payment_date;
    private ?string $due_date;
    private ?string $beneficiary_document;

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getAmountToPay(): ?float
    {
        return $this->amount_to_pay;
    }

    public function setAmountToPay(?float $amount_to_pay): void
    {
        $this->amount_to_pay = $amount_to_pay;
    }

    public function getPaymentDate(): ?string
    {
        return $this->payment_date;
    }

    public function setPaymentDate(?string $payment_date): void
    {
        $this->payment_date = $payment_date;
    }

    public function getDueDate(): ?string
    {
        return $this->due_date;
    }

    public function setDueDate(?string $due_date): void
    {
        $this->due_date = $due_date;
    }

    public function getBeneficiaryDocument(): ?string
    {
        return $this->beneficiary_document;
    }

    public function setBeneficiaryDocument(?string $beneficiary_document): void
    {
        $this->beneficiary_document = $beneficiary_document;
    }

    public function __construct(
        ?string $barcode = null,
        ?float $amount_to_pay = null,
        ?string $payment_date = null,
        ?string $due_date = null,
        ?string $beneficiary_document = null
    ) {
        $this->barcode = $barcode;
        $this->amount_to_pay = $amount_to_pay;
        $this->payment_date = $payment_date;
        $this->due_date = $due_date;
        $this->beneficiary_document = $beneficiary_document;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['codBarraLinhaDigitavel'] ?? null,
            isset($json['valorPagar']) ? (float)$json['valorPagar'] : null,
            $json['dataPagamento'] ?? null,
            $json['dataVencimento'] ?? null,
            $json['cpfCnpjBeneficiario'] ?? null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "codBarraLinhaDigitavel" => $this->barcode,
            "valorPagar" => $this->amount_to_pay,
            "dataPagamento" => $this->payment_date,
            "dataVencimento" => $this->due_date,
            "cpfCnpjBeneficiario" => $this->beneficiary_document,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "codBarraLinhaDigitavel" => $this->barcode,
            "valorPagar" => $this->amount_to_pay,
            "dataPagamento" => $this->payment_date,
            "dataVencimento" => $this->due_date,
            "cpfCnpjBeneficiario" => $this->beneficiary_document,
        ];
    }
}