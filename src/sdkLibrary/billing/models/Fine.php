<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

use Inter\Sdk\sdkLibrary\billing\enums\FineCode;

/**
 * Represents a fine with a specific code, rate, and value.
 *
 * This class allows you to define a fine that can have a unique code,
 * a specified rate, and a monetary value. It also supports
 * additional fields, which can be used to store any extra information
 * related to the fine in a flexible manner.
 *
 * All fields are serializable to and from JSON format. The class is designed
 * to be flexible and can handle dynamic fields that are not strictly defined
 * within the class.
 */
class Fine
{
    private ?FineCode $code;
    private ?float $rate;
    private ?float $value;

    public function __construct(
        ?FineCode $code = null,
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
            isset($json['codigo']) ? FineCode::fromString($json['codigo']) : null,
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