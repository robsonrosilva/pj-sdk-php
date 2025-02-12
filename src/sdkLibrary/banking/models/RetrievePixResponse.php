<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The RetrievePixResponse class represents the response
 * received when retrieving a PIX transaction and its history.
 */
class RetrievePixResponse
{
    private ?PixTransaction $pix_transaction;
    private array $history; // List of PixHistoryEntity objects

    public function __construct(?PixTransaction $pix_transaction = null, ?array $history = null)
    {
        $this->pix_transaction = $pix_transaction;
        $this->history = $history;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            isset($json['transacaoPix']) ? PixTransaction::fromJson($json['transacaoPix']) : null,
            array_map(fn($item) => PixHistoryEntity::fromJson($item), $json['historico'] ?? [])
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
            "transacaoPix" => $this->pix_transaction?->toArray(),
            "historico" => array_map(fn($item) => $item->toArray(), $this->history)
        ];
    }
}