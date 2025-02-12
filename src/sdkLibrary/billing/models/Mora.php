<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

use Inter\Sdk\sdkLibrary\billing\enums\MoraCode;

/**
 * The Mora class represents the interest applied to an overdue
 * payment.
 *
 * It includes details such as the interest code, the percentage rate
 * of the interest, and the total amount of the interest. This class is used
 * to map data from a JSON structure, allowing the deserialization of
 * received information.
 */
class Mora
{
    private ?MoraCode $code;
    private ?float $rate;
    private ?float $value;

    public function __construct(
        ?MoraCode $code = null,
        ?float $rate = null,
        ?float $value = null
    ) {
        $this->code = $code;
        $this->rate = $rate;
        $this->value = $value;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            isset($json['codigo']) ? MoraCode::fromString($json['codigo']) : null,
            isset($json['taxa']) ? ($json['taxa']) : null,
            isset($json['valor']) ? ($json['valor']) : null
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "codigo" => $this->code?->value,
            "taxa" => $this->rate ? floatval($this->rate) : null,
            "valor" => $this->value ? floatval($this->value) : null
        ];
    }
}