<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use DateMalformedStringException;

/**
 * The GeneratedImmediateBilling class represents a generated
 * immediate billing transaction.
 *
 * It includes various fields that describe the billing
 * details, such as keys, payer requests, debtor and receiver
 * information, billing values, and additional dynamic fields.
 */
class GeneratedImmediateBilling
{
    private ?string $key;
    private ?string $payer_request;
    private array $additional_info;
    private ?string $pix_copy_paste;
    private ?Debtor $debtor;
    private ?Receiver $receiver;
    private ?Location $loc;
    private ?string $location;
    private ?string $status;
    private ?PixValue $value;
    private ?Calendar $calendar;
    private ?string $txid;
    private ?int $revision;

    public function __construct(
        ?string $key = null,
        ?string $payer_request = null,
        array $additional_info = [],
        ?string $pix_copy_paste = null,
        ?Debtor $debtor = null,
        ?Receiver $receiver = null,
        ?Location $loc = null,
        ?string $location = null,
        ?string $status = null,
        ?PixValue $value = null,
        ?Calendar $calendar = null,
        ?string $txid = null,
        ?int $revision = null
    ) {
        $this->key = $key;
        $this->payer_request = $payer_request;
        $this->additional_info = $additional_info;
        $this->pix_copy_paste = $pix_copy_paste;
        $this->debtor = $debtor;
        $this->receiver = $receiver;
        $this->loc = $loc;
        $this->location = $location;
        $this->status = $status;
        $this->value = $value;
        $this->calendar = $calendar;
        $this->txid = $txid;
        $this->revision = $revision;
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

    public function getPixCopyPaste(): ?string
    {
        return $this->pix_copy_paste;
    }

    public function setPixCopyPaste(?string $pix_copy_paste): void
    {
        $this->pix_copy_paste = $pix_copy_paste;
    }

    public function getDebtor(): ?Debtor
    {
        return $this->debtor;
    }

    public function setDebtor(?Debtor $debtor): void
    {
        $this->debtor = $debtor;
    }

    public function getReceiver(): ?Receiver
    {
        return $this->receiver;
    }

    public function setReceiver(?Receiver $receiver): void
    {
        $this->receiver = $receiver;
    }

    public function getLoc(): ?Location
    {
        return $this->loc;
    }

    public function setLoc(?Location $loc): void
    {
        $this->loc = $loc;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getValue(): ?PixValue
    {
        return $this->value;
    }

    public function setValue(?PixValue $value): void
    {
        $this->value = $value;
    }

    public function getCalendar(): ?Calendar
    {
        return $this->calendar;
    }

    public function setCalendar(?Calendar $calendar): void
    {
        $this->calendar = $calendar;
    }

    public function getTxid(): ?string
    {
        return $this->txid;
    }

    public function setTxid(?string $txid): void
    {
        $this->txid = $txid;
    }

    public function getRevision(): ?int
    {
        return $this->revision;
    }

    public function setRevision(?int $revision): void
    {
        $this->revision = $revision;
    }

    /**
     * @throws DateMalformedStringException
     */
    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['chave'] ?? null,
            $data['solicitacaoPagador'] ?? null,
            array_map(fn($info) => AdditionalInfo::fromJson($info), $data['infoAdicionais'] ?? []),
            $data['pixCopiaECola'] ?? null,
            isset($data['devedor']) ? Debtor::fromJson($data['devedor']) : null,
            isset($data['recebedor']) ? Receiver::fromJson($data['recebedor']) : null,
            isset($data['loc']) ? Location::fromJson($data['loc']) : null,
            $data['location'] ?? null,
            $data['status'] ?? null,
            isset($data['valor']) ? PixValue::fromJson($data['valor']) : null,
            isset($data['calendario']) ? Calendar::fromJson($data['calendario']) : null,
            $data['txid'] ?? null,
            $data['revisao'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "chave" => $this->key,
            "solicitacaoPagador" => $this->payer_request,
            "infoAdicionais" => array_map(fn($info) => $info->toArray(), $this->additional_info),
            "pixCopiaECola" => $this->pix_copy_paste,
            "devedor" => $this->debtor?->toArray(),
            "recebedor" => $this->receiver?->toArray(),
            "loc" => $this->loc?->toArray(),
            "location" => $this->location,
            "status" => $this->status,
            "valor" => $this->value?->toArray(),
            "calendario" => $this->calendar?->toArray(),
            "txid" => $this->txid,
            "revisao" => $this->revision
        ];
    }
}