<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The IncludeBatchPaymentResponse class represents the response for an include batch payment request,
 * including the batch ID, status, custom identifier, and the quantity of payments.
 */
class IncludeBatchPaymentResponse
{
    private ?string $batch_id;
    private ?string $status;
    private ?string $my_identifier;
    private ?int $payment_quantity;

    public function __construct(?string $batch_id = null, ?string $status = null, ?string $my_identifier = null, ?int $payment_quantity = null)
    {
        $this->batch_id = $batch_id;
        $this->status = $status;
        $this->my_identifier = $my_identifier;
        $this->payment_quantity = $payment_quantity;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['idLote'] ?? null,
            $json['status'] ?? null,
            $json['meuIdentificador'] ?? null,
            $json['qtdePagamentos'] ?? null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "idLote" => $this->batch_id,
            "status" => $this->status,
            "meuIdentificador" => $this->my_identifier,
            "qtdePagamentos" => $this->payment_quantity,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "idLote" => $this->batch_id,
            "status" => $this->status,
            "meuIdentificador" => $this->my_identifier,
            "qtdePagamentos" => $this->payment_quantity,
        ];
    }
}