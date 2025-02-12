<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The CallbackRetrieveFilter class is used to filter callback requests
 * based on specific criteria, such as the transaction ID (txid).
 *
 * This class provides a structured way to specify filters
 * when retrieving callback data, allowing for efficient searches based
 * on transaction identifiers.
 */
class CallbackRetrieveFilter
{
    private ?string $txid;

    public function __construct(?string $txid = null)
    {
        $this->txid = $txid;
    }

    public function getTxid(): ?string
    {
        return $this->txid;
    }

    public function setTxid(?string $txid): void
    {
        $this->txid = $txid;
    }

    public static function fromJson(array $data): self
    {
        return new self(
            $data['txid'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "txid" => $this->txid
        ];
    }
}