<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The IncludePaymentResponse class represents the response for including a payment,
 * including approver details, payment status, transaction code, title, and message.
 */
class IncludePaymentResponse
{
    private ?int $number_of_approvers;
    private ?string $payment_status;
    private ?string $transaction_code;
    private ?string $title;
    private ?string $message;

    public function __construct(?int $number_of_approvers = null, ?string $payment_status = null, ?string $transaction_code = null, ?string $title = null, ?string $message = null)
    {
        $this->number_of_approvers = $number_of_approvers;
        $this->payment_status = $payment_status;
        $this->transaction_code = $transaction_code;
        $this->title = $title;
        $this->message = $message;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['quantidadeAprovadores'] ?? null,
            $json['statusPagamento'] ?? null,
            $json['codigoTransacao'] ?? null,
            $json['titulo'] ?? null,
            $json['mensagem'] ?? null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "quantidadeAprovadores" => $this->number_of_approvers,
            "statusPagamento" => $this->payment_status,
            "codigoTransacao" => $this->transaction_code,
            "titulo" => $this->title,
            "mensagem" => $this->message,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "quantidadeAprovadores" => $this->number_of_approvers,
            "statusPagamento" => $this->payment_status,
            "codigoTransacao" => $this->transaction_code,
            "titulo" => $this->title,
            "mensagem" => $this->message,
        ];
    }
}