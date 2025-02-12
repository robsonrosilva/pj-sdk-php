<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The AdditionalInfo class represents extra information
 * that can be associated with a transaction or entity.
 *
 * It includes fields for the name and value of the
 * additional information, allowing enhanced details to be captured
 * within the transaction context.
 */
class AdditionalInfo
{
    private ?string $name;
    private ?string $value;

    public function __construct(
        ?string $name = null,
        ?string $value = null
    ) {
        $this->name = $name;
        $this->value = $value;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['nome'] ?? null,
            $data['valor'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "nome" => $this->name,
            "valor" => $this->value
        ];
    }
}