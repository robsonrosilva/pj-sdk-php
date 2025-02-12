<?php
namespace Inter\Sdk\sdkLibrary\banking\models;
use JsonException;


/**
 * The Batch class represents a collection of payments.
 */
class Batch
{
    private ?string $my_identifier;
    private ?array $payments;
    public function __construct(?string $my_identifier, ?array $payments)
    {
        $this->my_identifier = $my_identifier;
        $this->payments = $payments;
    }
    public static function fromJson(mixed $json): self
    {
        $payments = [];
        foreach ($json['pagamentos'] ?? [] as $item) {
            if ($item['tipoPagamento'] === "DARF") {
                $payments[] = DarfPaymentBatch::fromJson($item);
            } elseif ($item['tipoPagamento'] === "BOLETO") {
                $payments[] = BilletBatch::fromJson($item);
            }
        }
        return new self(
            $json['meuIdentificador'] ?? null,
            $payments
        );
    }
    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $concatenated_payments = [];
        if ($this->payments) {
            foreach ($this->payments as $payment) {
                if ($payment instanceof DarfPaymentBatch) {
                    $concatenated_payments[] = $payment->toArray();
                } elseif ($payment instanceof BilletBatch) {
                    $concatenated_payments[] = $payment->toArray();
                }
            }
        }
        $obj = [
            "meuIdentificador" => $this->my_identifier,
            "pagamentos" => $concatenated_payments,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }
}