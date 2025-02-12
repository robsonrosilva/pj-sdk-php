<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The DueBilling class represents the details of a billing
 * transaction that is due for payment.
 *
 * It includes fields for a unique key, payer's request,
 * additional information, debtor details, location, due billing
 * value, due billing calendar, and transaction ID (txid). It also
 * supports additional custom fields through a map of additional
 * attributes.
 */
class DueBilling
{
    private ?string $key;
    private ?string $payer_request;
    private array $additional_info;
    private ?Debtor $debtor;
    private ?Location $location;
    private ?DueBillingValue $value;
    private ?DueBillingCalendar $calendar;
    private ?string $txid;

    public function __construct(
        ?string $key = null,
        ?string $payer_request = null,
        array $additional_info = [],
        ?Debtor $debtor = null,
        ?Location $location = null,
        ?DueBillingValue $value = null,
        ?DueBillingCalendar $calendar = null,
        ?string $txid = null
    ) {
        $this->key = $key;
        $this->payer_request = $payer_request;
        $this->additional_info = $additional_info;
        $this->debtor = $debtor;
        $this->location = $location;
        $this->value = $value;
        $this->calendar = $calendar;
        $this->txid = $txid;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['chave'] ?? null,
            $data['solicitacaoPagador'] ?? null,
            array_map(fn($info) => AdditionalInfo::fromJson($info), $data['infoAdicionais'] ?? []),
            isset($data['devedor']) ? Debtor::fromJson($data['devedor']) : null,
            isset($data['loc']) ? Location::fromJson($data['loc']) : null,
            isset($data['valor']) ? DueBillingValue::fromJson($data['valor']) : null,
            isset($data['calendario']) ? DueBillingCalendar::fromJson($data['calendario']) : null,
            $data['txid'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "chave" => $this->key,
            "solicitacaoPagador" => $this->payer_request,
            "infoAdicionais" => array_map(fn($info) => $info->toArray(), $this->additional_info),
            "devedor" => $this->debtor?->toArray(),
            "loc" => $this->location?->toArray(),
            "valor" => $this->value?->toArray(),
            "calendario" => $this->calendar?->toArray(),
            "txid" => $this->txid
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}