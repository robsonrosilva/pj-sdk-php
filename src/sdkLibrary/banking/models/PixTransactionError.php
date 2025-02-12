<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The PixTransactionError class represents an error associated with
 * a PIX transaction, including error codes and descriptions.
 */
class PixTransactionError
{
    private ?string $error_code;
    private ?string $error_description;
    private ?string $complementary_error_code;

    public function __construct(?string $error_code = null, ?string $error_description = null, ?string $complementary_error_code = null)
    {
        $this->error_code = $error_code;
        $this->error_description = $error_description;
        $this->complementary_error_code = $complementary_error_code;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['codigoErro'] ?? null,
            $json['descricaoErro'] ?? null,
            $json['codigoErroComplementar'] ?? null
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
            "codigoErro" => $this->error_code,
            "descricaoErro" => $this->error_description,
            "codigoErroComplementar" => $this->complementary_error_code
        ];
    }
}