<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

use JsonException;

/**
 * The BillingBilletRetrievingResponse class represents the response
 * received when retrieving billing billet details, including
 * our number, barcode, and digit line.
 */
class BillingBilletRetrievingResponse
{
    private ?string $our_number;
    private ?string $barcode;
    private ?string $digit_line;

    public function __construct(?string $our_number = null, ?string $barcode = null, ?string $digit_line = null)
    {
        $this->our_number = $our_number;
        $this->barcode = $barcode;
        $this->digit_line = $digit_line;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['nossoNumero'] ?? null,
            $json['codigoBarras'] ?? null,
            $json['linhaDigitavel'] ?? null
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
            "nossoNumero" => $this->our_number,
            "codigoBarras" => $this->barcode,
            "linhaDigitavel" => $this->digit_line
        ];
    }
}