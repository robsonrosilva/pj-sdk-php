<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The BilletBatch class represents a batch of boleto payments.
 */
class BilletBatch
{
    private string $payment_type = "BOLETO";
    private ?string $detail;
    private ?string $transaction_id;
    private ?string $status;
    private ?string $barcode;
    private ?float $amount_to_pay;
    private ?string $payment_date;
    private ?string $due_date;
    private ?string $beneficiary_document;
    public function __construct(
        ?string $detail = null,
        ?string $transaction_id = null,
        ?string $status = null,
        ?string $barcode = null,
        ?float $amount_to_pay = null,
        ?string $payment_date = null,
        ?string $due_date = null,
        ?string $beneficiary_document = null
    ) {
        $this->detail = $detail;
        $this->transaction_id = $transaction_id;
        $this->status = $status;
        $this->barcode = $barcode;
        $this->amount_to_pay = $amount_to_pay;
        $this->payment_date = $payment_date;
        $this->due_date = $due_date;
        $this->beneficiary_document = $beneficiary_document;
    }
    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['detalhe'] ?? null,
            $json['idTransacao'] ?? null,
            $json['status'] ?? null,
            $json['codBarraLinhaDigitavel'] ?? null,
            $json['valorPagar'] ?? null,
            $json['dataPagamento'] ?? null,
            $json['dataVencimento'] ?? null,
            $json['cpfCnpjBeneficiario'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "tipoPagamento" => $this->payment_type,
            "detalhe" => $this->detail,
            "idTransacao" => $this->transaction_id,
            "status" => $this->status,
            "codBarraLinhaDigitavel" => $this->barcode,
            "valorPagar" => $this->amount_to_pay,
            "dataPagamento" => $this->payment_date,
            "dataVencimento" => $this->due_date,
            "cpfCnpjBeneficiario" => $this->beneficiary_document,
        ];
    }
}