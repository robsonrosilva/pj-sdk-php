<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use Inter\Sdk\sdkLibrary\banking\enums\PixStatus;
use JsonException;

/**
 * The PixHistoryEntity class represents a historical entry
 * for a PIX transaction, including its status and event date/time.
 */
class PixHistoryEntity
{
    private ?PixStatus $status;
    private ?string $event_date_time;

    public function __construct(?PixStatus $status = null, ?string $event_date_time = null)
    {
        $this->status = $status;
        $this->event_date_time = $event_date_time;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            isset($json['status']) ? PixStatus::fromString($json['status']): null,
            $json['dataHoraEvento'] ?? null
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
            "status" => $this->status ? $this->status->value : null,
            "dataHoraEvento" => $this->event_date_time
        ];
    }
}