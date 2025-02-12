<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The ComponentValue class represents a component associated
 * with a monetary value.
 *
 * It includes the value, the agent's modality, and the
 * service provider responsible for handling withdrawal transactions.
 */
class ComponentValue
{
    private ?string $value;
    private ?string $agent_modality;
    private ?string $withdrawal_service_provider;

    public function __construct(
        ?string $value = null,
        ?string $agent_modality = null,
        ?string $withdrawal_service_provider = null
    ) {
        $this->value = $value;
        $this->agent_modality = $agent_modality;
        $this->withdrawal_service_provider = $withdrawal_service_provider;
    }

    public static function fromJson(array $data): self
    {
        return new self(
            $data['valor'] ?? null,
            $data['modalidadeAgente'] ?? null,
            $data['prestadorDoServicoDeSaque'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "valor" => $this->value,
            "modalidadeAgente" => $this->agent_modality,
            "prestadorDoServicoDeSaque" => $this->withdrawal_service_provider
        ];
    }
}