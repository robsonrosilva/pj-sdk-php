<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The ItemPayload class represents the payload for a transaction item,
 * containing various attributes such as the key, value components,
 * devolution requests, transaction IDs, timestamps, and payer information.
 */
class ItemPayload
{
    private ?string $key;
    private ?ValueComponent $value_components;
    private array $devolutions;
    private ?string $end_to_end_id;
    private ?string $timestamp;
    private ?string $payer_info;
    private ?string $txid;
    private ?string $value;

    public function __construct(
        ?string $key = null,
        ?ValueComponent $value_components = null,
        array $devolutions = [],
        ?string $end_to_end_id = null,
        ?string $timestamp = null,
        ?string $payer_info = null,
        ?string $txid = null,
        ?string $value = null
    ) {
        $this->key = $key;
        $this->value_components = $value_components;
        $this->devolutions = $devolutions;
        $this->end_to_end_id = $end_to_end_id;
        $this->timestamp = $timestamp;
        $this->payer_info = $payer_info;
        $this->txid = $txid;
        $this->value = $value;
    }

    public static function fromJson(array $data): self
    {
        return new self(
            $data['chave'] ?? null,
            isset($data['componentesValor']) ? ValueComponent::fromJson($data['componentesValor']) : null,
            array_map(fn($dev) => DevolutionRequestBody::fromJson($dev), $data['devolucoes'] ?? []),
            $data['endToEndId'] ?? null,
            $data['horario'] ?? null,
            $data['infoPagador'] ?? null,
            $data['txid'] ?? null,
            $data['valor'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "chave" => $this->key,
            "componentesValor" => $this->value_components?->toArray(),
            "devolucoes" => array_map(fn($dev) => $dev->toArray(), $this->devolutions),
            "endToEndId" => $this->end_to_end_id,
            "horario" => $this->timestamp,
            "infoPagador" => $this->payer_info,
            "txid" => $this->txid,
            "valor" => $this->value
        ];
    }
}