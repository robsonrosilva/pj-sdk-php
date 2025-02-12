<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use Inter\Sdk\sdkLibrary\pix\enums\AgentModality;

/**
 * The Withdrawal class represents details regarding a withdrawal
 * transaction. It includes fields such as the amount of the withdrawal,
 * the modification modality, the agent modality used for the transaction,
 * and the service provider responsible for the withdrawal.
 */
class Withdrawal
{
    private ?string $amount;
    private ?int $modification_modality;
    private ?AgentModality $agent_modality;
    private ?string $withdrawal_service_provider;

    public function __construct(
        ?string $amount = null,
        ?int $modification_modality = null,
        ?AgentModality $agent_modality = null,
        ?string $withdrawal_service_provider = null
    ) {
        $this->amount = $amount;
        $this->modification_modality = $modification_modality;
        $this->agent_modality = $agent_modality;
        $this->withdrawal_service_provider = $withdrawal_service_provider;
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
            "modalidadeAgente" => $this->agent_modality?->getValue(),
            "prestadorDoServicoDeSaque" => $this->withdrawal_service_provider
        ];
    }
}