<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The Pix class represents a PIX payment transaction,
 * including details such as amount, payment date, description, and recipient.
 */
class Pix
{
    private ?string $amount;
    private ?string $payment_date;
    private ?string $description;
    private ?Recipient $recipient;

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(?string $amount): void
    {
        $this->amount = $amount;
    }

    public function getPaymentDate(): ?string
    {
        return $this->payment_date;
    }

    public function setPaymentDate(?string $payment_date): void
    {
        $this->payment_date = $payment_date;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getRecipient(): ?\Inter\Sdk\sdkLibrary\banking\models\Recipient
    {
        return $this->recipient;
    }

    public function setRecipient(?\Inter\Sdk\sdkLibrary\banking\models\Recipient $recipient): void
    {
        $this->recipient = $recipient;
    }

    public function __construct(?string $amount = null, ?string $payment_date = null, ?string $description = null, ?Recipient $recipient = null)
    {
        $this->amount = $amount;
        $this->payment_date = $payment_date;
        $this->description = $description;
        $this->recipient = $recipient;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['valor'] ?? null,
            $json['dataPagamento'] ?? null,
            $json['descricao'] ?? null,
            isset($json['destinatario']) ? Recipient::createFromJson($json['destinatario']) : null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "valor" => $this->amount,
            "dataPagamento" => $this->payment_date,
            "descricao" => $this->description,
            "destinatario" => $this->recipient?->toArray()
        ];
    }
}