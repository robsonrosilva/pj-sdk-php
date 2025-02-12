<?php
namespace Inter\Sdk\sdkLibrary\billing\models;
use Inter\Sdk\sdkLibrary\billing\enums\BillingSituation;
use Inter\Sdk\sdkLibrary\billing\enums\BillingType;
use Inter\Sdk\sdkLibrary\billing\enums\ReceivingOrigin;

/**
 * The BillingRetrievingResponse class represents the response received
 * when retrieving billing information.
 *
 * It contains various details including the request code, issue number,
 * issue and due dates, nominal value, billing type, billing situation, total amount
 * received, discounts, fines, interest, and payer information.
 */
class BillingRetrievingResponse
{
    private ?string $request_code;
    private ?string $your_number;
    private ?string $issue_date;
    private ?string $due_date;
    private ?float $nominal_value;
    private ?BillingType $billing_type;
    private ?BillingSituation $situation;
    private ?string $situation_date;
    private ?string $total_amount_received;
    private ?ReceivingOrigin $receiving_origin;
    private ?string $cancellation_reason;
    private ?bool $archived;
    private array $discounts; // List of Discount objects
    private ?Fine $fine;
    private ?Mora $interest;
    private ?Person $payer;
    public function __construct(
        ?string $request_code = null,
        ?string $your_number = null,
        ?string $issue_date = null,
        ?string $due_date = null,
        ?float $nominal_value = null,
        ?BillingType $billing_type = null,
        ?BillingSituation $situation = null,
        ?string $situation_date = null,
        ?string $total_amount_received = null,
        ?ReceivingOrigin $receiving_origin = null,
        ?string $cancellation_reason = null,
        ?bool $archived = null,
        array $discounts = null,
        ?Fine $fine = null,
        ?Mora $interest = null,
        ?Person $payer = null
    ) {
        $this->request_code = $request_code;
        $this->your_number = $your_number;
        $this->issue_date = $issue_date;
        $this->due_date = $due_date;
        $this->nominal_value = $nominal_value;
        $this->billing_type = $billing_type;
        $this->situation = $situation;
        $this->situation_date = $situation_date;
        $this->total_amount_received = $total_amount_received;
        $this->receiving_origin = $receiving_origin;
        $this->cancellation_reason = $cancellation_reason;
        $this->archived = $archived;
        $this->discounts = $discounts;
        $this->fine = $fine;
        $this->interest = $interest;
        $this->payer = $payer;
    }
    public static function fromJson(array $json): self
    {
        return new self(
            $json['codigoSolicitacao'] ?? null,
            $json['seuNumero'] ?? null,
            $json['dataEmissao'] ?? null,
            $json['dataVencimento'] ?? null,
            $json['valorNominal'] ?? null,
            isset($json['tipoCobranca']) ? BillingType::fromString($json['tipoCobranca']) : null,
            isset($json['situacao']) ? BillingSituation::fromString($json['situacao']) : null,
            $json['dataSituacao'] ?? null,
            $json['valorTotalRecebido'] ?? null,
            isset($json['origemRecebimento']) ? ReceivingOrigin::fromString($json['origemRecebimento']) : null,
            $json['motivoCancelamento'] ?? null,
            $json['arquivada'] ?? null,
            array_map(fn($d) => Discount::fromJson($d), $json['descontos'] ?? []),
            isset($json['multa']) ? Fine::fromJson($json['multa']) : null,
            isset($json['mora']) ? Mora::fromJson($json['mora']) : null,
            isset($json['pagador']) ? Person::fromJson($json['pagador']) : null
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
    public function toArray(): array
    {
        return [
            "codigoSolicitacao" => $this->request_code,
            "seuNumero" => $this->your_number,
            "dataEmissao" => $this->issue_date,
            "dataVencimento" => $this->due_date,
            "valorNominal" => $this->nominal_value ? floatval($this->nominal_value) : null,
            "tipoCobranca" => $this->billing_type?->value,
            "situacao" => $this->situation?->value,
            "dataSituacao" => $this->situation_date,
            "valorTotalRecebido" => $this->total_amount_received,
            "origemRecebimento" => $this->receiving_origin?->value,
            "motivoCancelamento" => $this->cancellation_reason,
            "arquivada" => $this->archived,
            "descontos" => array_map(fn($discount) => $discount->toArray(), $this->discounts),
            "multa" => $this->fine?->toArray(),
            "mora" => $this->interest?->toArray(),
            "pagador" => $this->payer?->toArray()
        ];
    }
}