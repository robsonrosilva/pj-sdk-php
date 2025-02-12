<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

/**
 * The BillingIssueRequest class represents a request to issue a billing,
 * including details such as nominal value, due date, payer information,
 * and optional attributes like discounts, fines, and messages.
 */
class BillingIssueRequest
{
    private ?string $your_number;
    private ?float $nominal_value;
    private ?string $due_date;
    private ?int $scheduled_days;
    private ?Person $payer;
    private ?Discount $discount;
    private ?Fine $fine;
    private ?Mora $mora;
    private ?Message $message;
    private ?Person $final_beneficiary;
    private ?string $receiving_method;

    public function getYourNumber(): ?string
    {
        return $this->your_number;
    }

    public function setYourNumber(?string $your_number): void
    {
        $this->your_number = $your_number;
    }

    public function getNominalValue(): ?float
    {
        return $this->nominal_value;
    }

    public function setNominalValue(?float $nominal_value): void
    {
        $this->nominal_value = $nominal_value;
    }

    public function getDueDate(): ?string
    {
        return $this->due_date;
    }

    public function setDueDate(?string $due_date): void
    {
        $this->due_date = $due_date;
    }

    public function getScheduledDays(): ?int
    {
        return $this->scheduled_days;
    }

    public function setScheduledDays(?int $scheduled_days): void
    {
        $this->scheduled_days = $scheduled_days;
    }

    public function getPayer(): ?Person
    {
        return $this->payer;
    }

    public function setPayer(?Person $payer): void
    {
        $this->payer = $payer;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): void
    {
        $this->discount = $discount;
    }

    public function getFine(): ?Fine
    {
        return $this->fine;
    }

    public function setFine(?Fine $fine): void
    {
        $this->fine = $fine;
    }

    public function getMora(): ?Mora
    {
        return $this->mora;
    }

    public function setMora(?Mora $mora): void
    {
        $this->mora = $mora;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): void
    {
        $this->message = $message;
    }

    public function getFinalBeneficiary(): ?Person
    {
        return $this->final_beneficiary;
    }

    public function setFinalBeneficiary(?Person $final_beneficiary): void
    {
        $this->final_beneficiary = $final_beneficiary;
    }

    public function getReceivingMethod(): ?string
    {
        return $this->receiving_method;
    }

    public function setReceivingMethod(?string $receiving_method): void
    {
        $this->receiving_method = $receiving_method;
    }

    public function __construct(
        ?string $your_number = null,
        ?float $nominal_value = null,
        ?string $due_date = null,
        ?int $scheduled_days = null,
        ?Person $payer = null,
        ?Discount $discount = null,
        ?Fine $fine = null,
        ?Mora $mora = null,
        ?Message $message = null,
        ?Person $final_beneficiary = null,
        ?string $receiving_method = null
    ) {
        $this->your_number = $your_number;
        $this->nominal_value = $nominal_value;
        $this->due_date = $due_date;
        $this->scheduled_days = $scheduled_days;
        $this->payer = $payer;
        $this->discount = $discount;
        $this->fine = $fine;
        $this->mora = $mora;
        $this->message = $message;
        $this->final_beneficiary = $final_beneficiary;
        $this->receiving_method = $receiving_method;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            $json['seuNumero'] ?? null,
            isset($json['valorNominal']) ? ($json['valorNominal']) : null,
            $json['dataVencimento'] ?? null,
            $json['numDiasAgenda'] ?? null,
            isset($json['pagador']) ? Person::fromJson($json['pagador']) : null,
            isset($json['desconto']) ? Discount::fromJson($json['desconto']) : null,
            isset($json['multa']) ? Fine::fromJson($json['multa']) : null,
            isset($json['mora']) ? Mora::fromJson($json['mora']) : null,
            isset($json['mensagem']) ? Message::fromJson($json['mensagem']) : null,
            isset($json['beneficiarioFinal']) ? Person::fromJson($json['beneficiarioFinal']) : null,
            $json['formasRecebimento'] ?? null
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "seuNumero" => $this->your_number,
            "valorNominal" => $this->nominal_value ? strval($this->nominal_value) : null,
            "dataVencimento" => $this->due_date,
            "numDiasAgenda" => $this->scheduled_days,
            "pagador" => $this->payer?->toArray(),
            "desconto" => $this->discount?->toArray(),
            "multa" => $this->fine?->toArray(),
            "mora" => $this->mora?->toArray(),
            "mensagem" => $this->message?->toArray(),
            "beneficiarioFinal" => $this->final_beneficiary?->toArray(),
            "formasRecebimento" => $this->receiving_method
        ];
    }
}