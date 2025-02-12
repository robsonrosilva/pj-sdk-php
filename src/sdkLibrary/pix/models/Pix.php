<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use DateTime;
use DateTimeInterface;

/**
 * The Pix class represents information related to a Pix payment.
 * It includes fields such as the unique end-to-end identifier, transaction
 * ID (txid), the amount of the payment, the recipient's key used for the
 * transfer, the timestamp of the transaction, payer information, and a
 * list of detailed refunds associated with this payment.
 */
class Pix
{
    private ?string $end_to_end_id;
    private ?string $txid;
    private ?string $value;
    private ?string $key;
    private ?DateTime $timestamp;
    private ?string $payer_info;
    private array $refunds;
    private ?ValueComponent $value_components;

    public function __construct(
        ?string $end_to_end_id = null,
        ?string $txid = null,
        ?string $value = null,
        ?string $key = null,
        ?DateTime $timestamp = null,
        ?string $payer_info = null,
        array $refunds = [],
        ?ValueComponent $value_components = null
    ) {
        $this->end_to_end_id = $end_to_end_id;
        $this->txid = $txid;
        $this->value = $value;
        $this->key = $key;
        $this->timestamp = $timestamp;
        $this->payer_info = $payer_info;
        $this->refunds = $refunds;
        $this->value_components = $value_components;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['endToEndId'] ?? null,
            $data['txid'] ?? null,
            $data['valor'] ?? null,
            $data['chave'] ?? null,
            isset($data['horario']) ? new DateTime($data['horario']) : null,
            $data['infoPagador'] ?? null,
            array_map(fn($refund) => DetailedDevolution::fromJson($refund), $data['devolucoes'] ?? []),
            isset($data['componentesValor']) ? ValueComponent::fromJson($data['componentesValor']) : null
        );
    }

    public function toArray(): array
    {
        return [
            "endToEndId" => $this->end_to_end_id,
            "txid" => $this->txid,
            "valor" => $this->value,
            "chave" => $this->key,
            "horario" => $this->timestamp?->format(DateTimeInterface::ATOM),
            "infoPagador" => $this->payer_info,
            "devolucoes" => array_map(fn($refund) => $refund->toArray(), $this->refunds),
            "componentesValor" => $this->value_components?->toArray()
        ];
    }
}