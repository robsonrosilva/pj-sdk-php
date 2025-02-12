<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The Violation class represents a violation related to a
 * financial transaction or business rule. It includes details such
 * as the reason for the violation, the specific property affected,
 * and the value associated with the violation.
 */
class Violation
{
    private ?string $reason;
    private ?string $property;
    private ?string $value;

    public function __construct(
        ?string $reason = null,
        ?string $property = null,
        ?string $value = null
    ) {
        $this->reason = $reason;
        $this->property = $property;
        $this->value = $value;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['razao'] ?? null,
            $data['propriedade'] ?? null,
            $data['valor'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "razao" => $this->reason,
            "propriedade" => $this->property,
            "valor" => $this->value
        ];
    }
}