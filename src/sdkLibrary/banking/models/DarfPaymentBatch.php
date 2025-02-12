<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The DarfPaymentBatch class represents a batch of DARF payments.
 */
class DarfPaymentBatch
{
    private string $payment_type = "DARF";
    private ?string $detail;
    private ?string $transaction_id;
    private ?string $status;
    private ?string $cnpj_or_cpf;
    private ?string $revenue_code;
    private ?string $due_date;
    private ?string $description;
    private ?string $enterprise_name;
    private ?string $enterprise_phone;
    private ?string $assessment_period;
    private ?string $payment_date;
    private ?string $inclusion_date;
    private ?float $value;
    private ?float $total_value;
    private ?float $fine_amount;
    private ?float $interest_amount;
    private ?string $reference;
    private ?string $darf_type;
    private ?string $type;
    private ?float $principal_value;
    public function __construct(
        ?string $detail = null,
        ?string $transaction_id = null,
        ?string $status = null,
        ?string $cnpj_or_cpf = null,
        ?string $revenue_code = null,
        ?string $due_date = null,
        ?string $description = null,
        ?string $enterprise_name = null,
        ?string $enterprise_phone = null,
        ?string $assessment_period = null,
        ?string $payment_date = null,
        ?string $inclusion_date = null,
        ?float $value = null,
        ?float $total_value = null,
        ?float $fine_amount = null,
        ?float $interest_amount = null,
        ?string $reference = null,
        ?string $darf_type = null,
        ?string $type = null,
        ?float $principal_value = null
    ) {
        $this->detail = $detail;
        $this->transaction_id = $transaction_id;
        $this->status = $status;
        $this->cnpj_or_cpf = $cnpj_or_cpf;
        $this->revenue_code = $revenue_code;
        $this->due_date = $due_date;
        $this->description = $description;
        $this->enterprise_name = $enterprise_name;
        $this->enterprise_phone = $enterprise_phone;
        $this->assessment_period = $assessment_period;
        $this->payment_date = $payment_date;
        $this->inclusion_date = $inclusion_date;
        $this->value = $value;
        $this->total_value = $total_value;
        $this->fine_amount = $fine_amount;
        $this->interest_amount = $interest_amount;
        $this->reference = $reference;
        $this->darf_type = $darf_type;
        $this->type = $type;
        $this->principal_value = $principal_value;
    }
    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['detalhe'] ?? null,
            $json['idTransacao'] ?? null,
            $json['status'] ?? null,
            $json['cnpjCpf'] ?? null,
            $json['codigoReceita'] ?? null,
            $json['dataVencimento'] ?? null,
            $json['descricao'] ?? null,
            $json['nomeEmpresa'] ?? null,
            $json['telefoneEmpresa'] ?? null,
            $json['periodoApuracao'] ?? null,
            $json['dataPagamento'] ?? null,
            $json['dataInclusao'] ?? null,
            $json['valor'] ?? null,
            $json['valorTotal'] ?? null,
            $json['valorMulta'] ?? null,
            $json['valorJuros'] ?? null,
            $json['referencia'] ?? null,
            $json['tipoDarf'] ?? null,
            $json['tipo'] ?? null,
            $json['valorPrincipal'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "tipoPagamento" => $this->payment_type,
            "detalhe" => $this->detail,
            "idTransacao" => $this->transaction_id,
            "status" => $this->status,
            "cnpjCpf" => $this->cnpj_or_cpf,
            "codigoReceita" => $this->revenue_code,
            "dataVencimento" => $this->due_date,
            "descricao" => $this->description,
            "nomeEmpresa" => $this->enterprise_name,
            "telefoneEmpresa" => $this->enterprise_phone,
            "periodoApuracao" => $this->assessment_period,
            "dataPagamento" => $this->payment_date,
            "dataInclusao" => $this->inclusion_date,
            "valor" => $this->value,
            "valorTotal" => $this->total_value,
            "valorMulta" => $this->fine_amount,
            "valorJuros" => $this->interest_amount,
            "referencia" => $this->reference,
            "tipoDarf" => $this->darf_type,
            "tipo" => $this->type,
            "valorPrincipal" => $this->principal_value,
        ];
    }
}