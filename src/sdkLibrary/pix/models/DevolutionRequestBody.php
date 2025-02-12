<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use Inter\Sdk\sdkLibrary\pix\enums\DevolutionNature;

/**
 * The DevolutionRequestBody class represents the body
 * of a request for a devolution (refund) operation.
 *
 * It includes the refund amount, nature of the devolution,
 * and a description to provide context for the refund request.
 */
class DevolutionRequestBody
{
    private ?string $value;
    private ?DevolutionNature $nature;
    private ?string $description;

    public function __construct(
        ?string $value = null,
        ?DevolutionNature $nature = null,
        ?string $description = null
    ) {
        $this->value = $value;
        $this->nature = $nature;
        $this->description = $description;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['valor'] ?? null,
            isset($data['natureza']) ? DevolutionNature::fromString($data['natureza']) : null,
            $data['descricao'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "valor" => $this->value,
            "natureza" => $this->nature?->value,
            "descricao" => $this->description
        ];
    }
}