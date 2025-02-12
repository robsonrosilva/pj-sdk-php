<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use Inter\Sdk\sdkLibrary\pix\enums\AgentModality;

/**
 * The Change class represents details regarding change to be
 * returned in a withdrawal transaction.
 *
 * It includes fields such as the amount of change, the
 * modification modality, the agent modality used for the transaction,
 * and the service provider responsible for the change service.
 */
class Change
{
    private ?string $amount;
    private ?int $modification_modality;
    private ?AgentModality $agent_modality;
    private ?string $change_service_provider;

    public function __construct(
        ?string $amount = null,
        ?int $modification_modality = null,
        ?AgentModality $agent_modality = null,
        ?string $change_service_provider = null
    ) {
        $this->amount = $amount;
        $this->modification_modality = $modification_modality;
        $this->agent_modality = $agent_modality;
        $this->change_service_provider = $change_service_provider;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['valor'] ?? null,
            $data['modalidadeAlteracao'] ?? null,
            isset($data['modalidadeAgente']) ? AgentModality::fromString($data['modalidadeAgente']) : null,
            $data['prestadorDoServicoDeSaque'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "valor" => $this->amount,
            "modalidadeAlteracao" => $this->modification_modality,
            "modalidadeAgente" => $this->agent_modality?->value,
            "prestadorDoServicoDeSaque" => $this->change_service_provider
        ];
    }
}