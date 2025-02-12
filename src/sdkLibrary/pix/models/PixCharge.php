<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The PixCharge class represents a payment request or transaction
 * details. It includes fields for the transaction ID (txid), calendar
 * details, debtor information, location, transaction value (PixValue),
 * key, payer request, and additional information.
 */
class PixCharge
{
    private ?string $transaction_id;
    private ?Calendar $calendar;
    private ?Debtor $debtor;
    private ?Location $loc;
    private ?string $location;
    private ?PixValue $value;
    private ?string $key;
    private ?string $payer_request;
    private array $additional_info;

    public function __construct(
        ?string $transaction_id = null,
        ?Calendar $calendar = null,
        ?Debtor $debtor = null,
        ?Location $loc = null,
        ?string $location = null,
        ?PixValue $value = null,
        ?string $key = null,
        ?string $payer_request = null,
        array $additional_info = []
    ) {
        $this->transaction_id = $transaction_id;
        $this->calendar = $calendar;
        $this->debtor = $debtor;
        $this->loc = $loc;
        $this->location = $location;
        $this->value = $value;
        $this->key = $key;
        $this->payer_request = $payer_request;
        $this->additional_info = $additional_info;
    }

    public static function fromJson(array $data): self
    {
        return new self(
            $data['txid'] ?? null,
            isset($data['calendario']) ? Calendar::fromJson($data['calendario']) : null,
            isset($data['devedor']) ? Debtor::fromJson($data['devedor']) : null,
            isset($data['loc']) ? Location::fromJson($data['loc']) : null,
            $data['location'] ?? null,
            isset($data['valor']) ? PixValue::fromJson($data['valor']) : null,
            $data['chave'] ?? null,
            $data['solicitacaoPagador'] ?? null,
            array_map(fn($info) => AdditionalInfo::fromJson($info), $data['infoAdicionais'] ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            "txid" => $this->transaction_id,
            "calendario" => $this->calendar?->toArray(),
            "devedor" => $this->debtor?->toArray(),
            "loc" => $this->loc?->toArray(),
            "location" => $this->location,
            "valor" => $this->value?->toArray(),
            "chave" => $this->key,
            "solicitacaoPagador" => $this->payer_request,
            "infoAdicionais" => array_map(fn($info) => $info->toArray(), $this->additional_info)
        ];
    }
}