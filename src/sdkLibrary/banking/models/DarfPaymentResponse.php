<?php
namespace Inter\Sdk\sdkLibrary\banking\models;
use JsonException;

/**
 * The DarfPaymentResponse class represents the response for a DARF payment request,
 * including various details about the payment, status, and amounts.
 */
class DarfPaymentResponse
{
    private ?string $request_code;
    private ?string $darf_type;
    private ?float $amount;
    private ?float $fine_amount;
    private ?float $interest_amount;
    private ?float $total_amount;
    private ?string $type;
    private ?string $assessment_period;
    private ?string $payment_date;
    private ?string $reference;
    private ?string $due_date;
    private ?string $revenue_code;
    private ?string $payment_status;
    private ?string $inclusion_date;
    private ?string $cnpj_cpf;
    private ?int $necessary_approvals;
    private ?int $realized_approvals;
    public function __construct(
        ?string $request_code = null,
        ?string $darf_type = null,
        ?float $amount = null,
        ?float $fine_amount = null,
        ?float $interest_amount = null,
        ?float $total_amount = null,
        ?string $type = null,
        ?string $assessment_period = null,
        ?string $payment_date = null,
        ?string $reference = null,
        ?string $due_date = null,
        ?string $revenue_code = null,
        ?string $payment_status = null,
        ?string $inclusion_date = null,
        ?string $cnpj_cpf = null,
        ?int $necessary_approvals = null,
        ?int $realized_approvals = null
    ) {
        $this->request_code = $request_code;
        $this->darf_type = $darf_type;
        $this->amount = $amount;
        $this->fine_amount = $fine_amount;
        $this->interest_amount = $interest_amount;
        $this->total_amount = $total_amount;
        $this->type = $type;
        $this->assessment_period = $assessment_period;
        $this->payment_date = $payment_date;
        $this->reference = $reference;
        $this->due_date = $due_date;
        $this->revenue_code = $revenue_code;
        $this->payment_status = $payment_status;
        $this->inclusion_date = $inclusion_date;
        $this->cnpj_cpf = $cnpj_cpf;
        $this->necessary_approvals = $necessary_approvals;
        $this->realized_approvals = $realized_approvals;
    }
    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['codigoSolicitacao'] ?? null,
            $json['tipoDarf'] ?? null,
            ($json['valor']) ?? null,
            ($json['valorMulta']) ?? null,
            ($json['valorJuros']) ?? null,
            ($json['valorTotal']) ?? null,
            $json['tipo'] ?? null,
            $json['periodoApuracao'] ?? null,
            $json['dataPagamento'] ?? null,
            $json['referencia'] ?? null,
            $json['dataVencimento'] ?? null,
            $json['codigoReceita'] ?? null,
            $json['statusPagamento'] ?? null,
            $json['dataInclusao'] ?? null,
            $json['cnpjCpf'] ?? null,
            $json['aprovacoesNecessarias'] ?? null,
            $json['aprovacoesRealizadas'] ?? null
        );
    }
    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "codigoSolicitacao" => $this->request_code,
            "tipoDarf" => $this->darf_type,
            "valor" => $this->amount !== null ? floatval($this->amount) : null,
            "valorMulta" => $this->fine_amount !== null ? floatval($this->fine_amount) : null,
            "valorJuros" => $this->interest_amount !== null ? floatval($this->interest_amount) : null,
            "valorTotal" => $this->total_amount !== null ? floatval($this->total_amount) : null,
            "tipo" => $this->type,
            "periodoApuracao" => $this->assessment_period,
            "dataPagamento" => $this->payment_date,
            "referencia" => $this->reference,
            "dataVencimento" => $this->due_date,
            "codigoReceita" => $this->revenue_code,
            "statusPagamento" => $this->payment_status,
            "dataInclusao" => $this->inclusion_date,
            "cnpjCpf" => $this->cnpj_cpf,
            "aprovacoesNecessarias" => $this->necessary_approvals,
            "aprovacoesRealizadas" => $this->realized_approvals,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }
    public function toArray(): array
    {
        return [
            "codigoSolicitacao" => $this->request_code,
            "tipoDarf" => $this->darf_type,
            "valor" => $this->amount !== null ? floatval($this->amount) : null,
            "valorMulta" => $this->fine_amount !== null ? floatval($this->fine_amount) : null,
            "valorJuros" => $this->interest_amount !== null ? floatval($this->interest_amount) : null,
            "valorTotal" => $this->total_amount !== null ? floatval($this->total_amount) : null,
            "tipo" => $this->type,
            "periodoApuracao" => $this->assessment_period,
            "dataPagamento" => $this->payment_date,
            "referencia" => $this->reference,
            "dataVencimento" => $this->due_date,
            "codigoReceita" => $this->revenue_code,
            "statusPagamento" => $this->payment_status,
            "dataInclusao" => $this->inclusion_date,
            "cnpjCpf" => $this->cnpj_cpf,
            "aprovacoesNecessarias" => $this->necessary_approvals,
            "aprovacoesRealizadas" => $this->realized_approvals,
        ];
    }
}