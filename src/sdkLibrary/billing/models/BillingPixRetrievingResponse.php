<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

/**
 * The BillingPixRetrievingResponse class represents the response received
 * when retrieving information about a Pix transaction.
 *
 * It contains the transaction identifier (txid) and the copy-paste
 * string of the Pix payment, allowing for easy transaction retrieval and processing.
 * This structure is utilized to map data from a JSON format, facilitating the
 * deserialization of the information received.
 */
class BillingPixRetrievingResponse
{
    private ?string $transaction_id;
    private ?string $pix_copy_and_paste;

    public function __construct(?string $transaction_id = null, ?string $pix_copy_and_paste = null)
    {
        $this->transaction_id = $transaction_id;
        $this->pix_copy_and_paste = $pix_copy_and_paste;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            $json['txid'] ?? null,
            $json['pixCopiaECola'] ?? null
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "txid" => $this->transaction_id,
            "pixCopiaECola" => $this->pix_copy_and_paste
        ];
    }
}
