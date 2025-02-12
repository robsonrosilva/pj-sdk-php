<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use DateMalformedStringException;

/**
 * The GeneratedDueBilling class represents a generated
 * due billing transaction.
 *
 * It includes various fields that describe the billing
 * details, such as keys, payer requests, debtor and receiver
 * information, billing values, and additional dynamic fields.
 */
class GeneratedDueBilling
{
    private ?string $key;
    private ?string $payer_request;
    private array $additional_info;
    private ?string $pix_copy_paste;
    private ?Debtor $debtor;
    private ?Receiver $receiver;
    private ?Location $location;
    private ?string $status;
    private ?DueBillingValue $value;
    private ?DueBillingCalendar $calendar;
    private ?string $txid;
    private ?int $revision;

    public function __construct(
        ?string $key = null,
        ?string $payer_request = null,
        array $additional_info = [],
        ?string $pix_copy_paste = null,
        ?Debtor $debtor = null,
        ?Receiver $receiver = null,
        ?Location $location = null,
        ?string $status = null,
        ?DueBillingValue $value = null,
        ?DueBillingCalendar $calendar = null,
        ?string $txid = null,
        ?int $revision = null
    ) {
        $this->key = $key;
        $this->payer_request = $payer_request;
        $this->additional_info = $additional_info;
        $this->pix_copy_paste = $pix_copy_paste;
        $this->debtor = $debtor;
        $this->receiver = $receiver;
        $this->location = $location;
        $this->status = $status;
        $this->value = $value;
        $this->calendar = $calendar;
        $this->txid = $txid;
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
            $data['status'] ?? null,
            isset($data['valor']) ? DueBillingValue::fromJson($data['valor']) : null,
            isset($data['calendario']) ? DueBillingCalendar::fromJson($data['calendario']) : null,
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
            "loc" => $this->location?->toArray(),
            "status" => $this->status,
            "valor" => $this->value?->toArray(),
            "calendario" => $this->calendar?->toArray(),
            "txid" => $this->txid,
            "revisao" => $this->revision
        ];
    }
}