<?php
namespace Inter\Sdk\sdkLibrary\banking\models;
use JsonException;

/**
 * The Payment class represents the details of a payment transaction,
 * including amounts, dates, beneficiary information, and approval status.
 */
class Payment
{
    private ?string $transaction_code;
    private ?string $barcode;
    private ?string $type;
    private ?string $entered_due_date;
    private ?string $title_due_date;
    private ?string $inclusion_date;
    private ?string $payment_date;
    private ?float $amount_paid;
    private ?float $nominal_amount;
    private ?string $payment_status;
    private ?int $required_approvals;
    private ?int $completed_approvals;
    private ?string $beneficiary_cpf_cnpj;
    private ?string $beneficiary_name;
    private ?string $authentication;
    public function __construct(
        ?string $transaction_code,
        ?string $barcode,
        ?string $type,
        ?string $entered_due_date,
        ?string $title_due_date,
        ?string $inclusion_date,
        ?string $payment_date,
        ?float $amount_paid,
        ?float $nominal_amount,
        ?string $payment_status,
        ?int $required_approvals,
        ?int $completed_approvals,
        ?string $beneficiary_cpf_cnpj,
        ?string $beneficiary_name,
        ?string $authentication
    ) {
        $this->transaction_code = $transaction_code;
        $this->barcode = $barcode;
        $this->type = $type;
        $this->entered_due_date = $entered_due_date;
        $this->title_due_date = $title_due_date;
        $this->inclusion_date = $inclusion_date;
        $this->payment_date = $payment_date;
        $this->amount_paid = $amount_paid;
        $this->nominal_amount = $nominal_amount;
        $this->payment_status = $payment_status;
        $this->required_approvals = $required_approvals;
        $this->completed_approvals = $completed_approvals;
        $this->beneficiary_cpf_cnpj = $beneficiary_cpf_cnpj;
        $this->beneficiary_name = $beneficiary_name;
        $this->authentication = $authentication;
    }
    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['codigoTransacao'] ?? null,
            $json['codigoBarra'] ?? null,
            $json['tipo'] ?? null,
            $json['dataVencimentoDigitada'] ?? null,
            $json['dataVencimentoTitulo'] ?? null,
            $json['dataInclusao'] ?? null,
            $json['dataPagamento'] ?? null,
            $json['valorPago'] ?? null,
            $json['valorNominal'] ?? null,
            $json['statusPagamento'] ?? null,
            $json['aprovacoesNecessarias'] ?? null,
            $json['aprovacoesRealizadas'] ?? null,
            $json['cpfCnpjBeneficiario'] ?? null,
            $json['nomeBeneficiario'] ?? null,
            $json['autenticacao'] ?? null
        );
    }
    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode([
            "codigoTransacao" => $this->transaction_code,
            "codigoBarra" => $this->barcode,
            "tipo" => $this->type,
            "dataVencimentoDigitada" => $this->entered_due_date,
            "dataVencimentoTitulo" => $this->title_due_date,
            "dataInclusao" => $this->inclusion_date,
            "dataPagamento" => $this->payment_date,
            "valorPago" => $this->amount_paid ? (string)$this->amount_paid : null,
            "valorNominal" => $this->nominal_amount ? (string)$this->nominal_amount : null,
            "statusPagamento" => $this->payment_status,
            "aprovacoesNecessarias" => $this->required_approvals,
            "aprovacoesRealizadas" => $this->completed_approvals,
            "cpfCnpjBeneficiario" => $this->beneficiary_cpf_cnpj,
            "nomeBeneficiario" => $this->beneficiary_name,
            "autenticacao" => $this->authentication
        ], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }
    public function toArray(): array
    {
        return [
            "codigoTransacao" => $this->transaction_code,
            "codigoBarra" => $this->barcode,
            "tipo" => $this->type,
            "dataVencimentoDigitada" => $this->entered_due_date,
            "dataVencimentoTitulo" => $this->title_due_date,
            "dataInclusao" => $this->inclusion_date,
            "dataPagamento" => $this->payment_date,
            "valorPago" => $this->amount_paid ? (string)$this->amount_paid : null,
            "valorNominal" => $this->nominal_amount ? (string)$this->nominal_amount : null,
            "statusPagamento" => $this->payment_status,
            "aprovacoesNecessarias" => $this->required_approvals,
            "aprovacoesRealizadas" => $this->completed_approvals,
            "cpfCnpjBeneficiario" => $this->beneficiary_cpf_cnpj,
            "nomeBeneficiario" => $this->beneficiary_name,
            "autenticacao" => $this->authentication
        ];
    }
}