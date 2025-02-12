<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The CallbackError class represents an error returned in a callback,
 * including an error code and a description.
 */
class CallbackError
{
    private ?string $error_code;
    private ?string $error_description;

    public function __construct(
        ?string $error_code = null,
        ?string $error_description = null
    ) {
        $this->error_code = $error_code;
        $this->error_description = $error_description;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['codigoErro'] ?? null,
            $json['descricaoErro'] ?? null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "codigoErro" => $this->error_code,
            "descricaoErro" => $this->error_description,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "codigoErro" => $this->error_code,
            "descricaoErro" => $this->error_description,
        ];
    }
}