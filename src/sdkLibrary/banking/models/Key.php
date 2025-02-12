<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The Key class represents a key used for PIX transactions,
 * inheriting from the Recipient class and including specific key information.
 */
class Key extends Recipient
{
    private string $type;
    private ?string $key;

    public function __construct(?string $key = null)
    {
        $this->type = "CHAVE";
        $this->key = $key;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['chave'] ?? null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "chave" => $this->key,
            "tipo" => $this->type,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "chave" => $this->key,
            "tipo" => $this->type,
        ];
    }
}