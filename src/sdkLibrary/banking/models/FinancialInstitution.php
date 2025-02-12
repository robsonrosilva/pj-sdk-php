<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The FinancialInstitution class represents a financial institution with its code,
 * name, ISPB, and type.
 */
class FinancialInstitution
{
    private ?string $code;
    private ?string $name;
    private ?string $ispb;
    private ?string $type;

    public function __construct(?string $code = null, ?string $name = null, ?string $ispb = null, ?string $type = null)
    {
        $this->code = $code;
        $this->name = $name;
        $this->ispb = $ispb;
        $this->type = $type;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['code'] ?? null,
            $json['name'] ?? null,
            $json['ispb'] ?? null,
            $json['type'] ?? null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "code" => $this->code,
            "name" => $this->name,
            "ispb" => $this->ispb,
            "type" => $this->type,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "code" => $this->code,
            "name" => $this->name,
            "ispb" => $this->ispb,
            "type" => $this->type,
        ];
    }
}