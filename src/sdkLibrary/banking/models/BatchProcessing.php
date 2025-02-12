<?php
namespace Inter\Sdk\sdkLibrary\banking\models;
use JsonException;

/**
 * The BatchProcessing class represents the details of a batch payment processing,
 * including bank account information, creation date, payment details, and status.
 */
class BatchProcessing
{
    private ?string $bank_account;
    private ?string $creation_date;
    private ?array $payments;
    private ?string $batch_id;
    private ?string $status;
    private ?string $my_identifier;
    private ?int $payment_quantity;
    public function __construct(
        ?string $bank_account = null,
        ?string $creation_date = null,
        ?array $payments = null,
        ?string $batch_id = null,
        ?string $status = null,
        ?string $my_identifier = null,
        ?int $payment_quantity = null
    ) {
        $this->bank_account = $bank_account;
        $this->creation_date = $creation_date;
        $this->payments = $payments;
        $this->batch_id = $batch_id;
        $this->status = $status;
        $this->my_identifier = $my_identifier;
        $this->payment_quantity = $payment_quantity;
    }
    public static function fromJson(mixed $json): self
    {
        $payments = [];
        if (isset($json['pagamentos'])) {
            foreach ($json['pagamentos'] as $item) {
                if ($item['tipoPagamento'] === "DARF") {
                    $payments[] = DarfPaymentBatch::fromJson($item);
                } elseif ($item['tipoPagamento'] === "BOLETO") {
                    $payments[] = BilletBatch::fromJson($item);
                }
            }
        }
        return new self(
            $json['contaCorrente'] ?? null,
            $json['dataCriacao'] ?? null,
            $payments,
            $json['idLote'] ?? null,
            $json['status'] ?? null,
            $json['meuIdentificador'] ?? null,
            $json['qtdePagamentos'] ?? null
        );
    }

    public function toArray(): array
    {
        $dict_payments = [];
        if ($this->payments) {
            foreach ($this->payments as $item) {
                if ($item instanceof DarfPaymentBatch) {
                    $dict_payments[] = $item->toArray();
                } elseif ($item instanceof BilletBatch) {
                    $dict_payments[] = $item->toArray();
                }
            }
        }
        return [
            "contaCorrente" => $this->bank_account,
            "dataCriacao" => $this->creation_date,
            "pagamentos" => $dict_payments,
            "idLote" => $this->batch_id,
            "status" => $this->status,
            "meuIdentificador" => $this->my_identifier,
            "qtdePagamentos" => $this->payment_quantity,
        ];

    }
    // Getters e Setters (opcional, se necessário)
    public function getBankAccount(): ?string
    {
        return $this->bank_account;
    }
    public function setBankAccount(?string $bank_account): void
    {
        $this->bank_account = $bank_account;
    }
    public function getCreationDate(): ?string
    {
        return $this->creation_date;
    }
    public function setCreationDate(?string $creation_date): void
    {
        $this->creation_date = $creation_date;
    }
    public function getPayments(): ?array
    {
        return $this->payments;
    }
    public function setPayments(?array $payments): void
    {
        $this->payments = $payments;
    }
    public function getBatchId(): ?string
    {
        return $this->batch_id;
    }
    public function setBatchId(?string $batch_id): void
    {
        $this->batch_id = $batch_id;
    }
    public function getStatus(): ?string
    {
        return $this->status;
    }
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
    public function getMyIdentifier(): ?string
    {
        return $this->my_identifier;
    }
    public function setMyIdentifier(?string $my_identifier): void
    {
        $this->my_identifier = $my_identifier;
    }
    public function getPaymentQuantity(): ?int
    {
        return $this->payment_quantity;
    }
    public function setPaymentQuantity(?int $payment_quantity): void
    {
        $this->payment_quantity = $payment_quantity;
    }
}