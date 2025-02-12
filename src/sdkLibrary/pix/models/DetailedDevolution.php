<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use Inter\Sdk\sdkLibrary\pix\enums\DevolutionNature;

/**
 * The DetailedDevolution class represents detailed information about a
 * refund process.
 *
 * It includes fields such as the refund ID, the return
 * transaction ID (rtrId), the amount of the refund, the current status,
 * and the reason for the refund. Additionally, it supports the inclusion
 * of any extra fields through a map for dynamic attributes that may not be
 * predefined. This structure is essential for managing and processing
 * refund-related information in billing systems.
 */
class DetailedDevolution
{
    private ?string $id;
    private ?string $rtr_id;
    private ?string $value;
    private ?string $status;
    private ?string $reason;
    private ?DevolutionNature $nature;
    private ?string $description;
    private ?CobMoment $moment;

    public function __construct(
        ?string $id = null,
        ?string $rtr_id = null,
        ?string $value = null,
        ?string $status = null,
        ?string $reason = null,
        ?DevolutionNature $nature = null,
        ?string $description = null,
        ?CobMoment $moment = null
    ) {
        $this->id = $id;
        $this->rtr_id = $rtr_id;
        $this->value = $value;
        $this->status = $status;
        $this->reason = $reason;
        $this->nature = $nature;
        $this->description = $description;
        $this->moment = $moment;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['rtrId'] ?? null,
            $data['valor'] ?? null,
            $data['status'] ?? null,
            $data['motivo'] ?? null,
            isset($data['natureza']) ? DevolutionNature::fromString($data['natureza']) : null,
            $data['descricao'] ?? null,
            isset($data['horario']) ? CobMoment::fromJson($data['horario']) : null
        );
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "rtrId" => $this->rtr_id,
            "valor" => $this->value,
            "status" => $this->status,
            "motivo" => $this->reason,
            "natureza" => $this->nature?->value,
            "descricao" => $this->description,
            "horario" => $this->moment?->toArray()
        ];
    }
}