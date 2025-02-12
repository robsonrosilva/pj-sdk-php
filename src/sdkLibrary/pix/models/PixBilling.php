<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The PixBilling class represents the detailed information
 * about a PIX billing transaction. It includes fields for the transaction
 * ID (txid), calendar details, debtor information, location, transaction
 * value (PixValue), key, payer request, and additional information.
 */
class PixBilling
{
    private ?string $txid;
    private ?Calendar $calendar;
    private ?Debtor $debtor;
    private ?Location $location;
    private ?PixValue $value;
    private ?string $key;
    private ?string $payer_request;
    private array $additional_info;

    public function __construct(
        ?string $txid = null,
        ?Calendar $calendar = null,
        ?Debtor $debtor = null,
        ?Location $location = null,
        ?PixValue $value = null,
        ?string $key = null,
        ?string $payer_request = null,
        array $additional_info = []
    ) {
        $this->txid = $txid;
        $this->calendar = $calendar;
        $this->debtor = $debtor;
        $this->location = $location;
        $this->value = $value;
        $this->key = $key;
        $this->payer_request = $payer_request;
        $this->additional_info = $additional_info;
    }

    public function getTxid(): ?string
    {
        return $this->txid;
    }

    public function setTxid(?string $txid): void
    {
        $this->txid = $txid;
    }

    public function getCalendar(): ?Calendar
    {
        return $this->calendar;
    }

    public function setCalendar(?Calendar $calendar): void
    {
        $this->calendar = $calendar;
    }

    public function getDebtor(): ?Debtor
    {
        return $this->debtor;
    }

    public function setDebtor(?Debtor $debtor): void
    {
        $this->debtor = $debtor;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): void
    {
        $this->location = $location;
    }

    public function getValue(): ?PixValue
    {
        return $this->value;
    }

    public function setValue(?PixValue $value): void
    {
        $this->value = $value;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

    public function getPayerRequest(): ?string
    {
        return $this->payer_request;
    }

    public function setPayerRequest(?string $payer_request): void
    {
        $this->payer_request = $payer_request;
    }

    public function getAdditionalInfo(): array
    {
        return $this->additional_info;
    }

    public function setAdditionalInfo(array $additional_info): void
    {
        $this->additional_info = $additional_info;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function fromJson(array $data): self
    {
        return new self(
            $data['txid'] ?? null,
            isset($data['calendario']) ? Calendar::fromJson($data['calendario']) : null,
            isset($data['devedor']) ? Debtor::fromJson($data['devedor']) : null,
            isset($data['loc']) ? Location::fromJson($data['loc']) : null,
            isset($data['valor']) ? PixValue::fromJson($data['valor']) : null,
            $data['chave'] ?? null,
            $data['solicitacaoPagador'] ?? null,
            array_map(fn($info) => AdditionalInfo::fromJson($info), $data['infoAdicionais'] ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            "txid" => $this->txid,
            "calendario" => $this->calendar?->toArray(),
            "devedor" => $this->debtor?->toArray(),
            "loc" => $this->location?->toArray(),
            "valor" => $this->value?->toArray(),
            "chave" => $this->key,
            "solicitacaoPagador" => $this->payer_request,
            "infoAdicionais" => array_map(fn($info) => $info->toArray(), $this->additional_info)
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}