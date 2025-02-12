<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use DateMalformedStringException;
use DateTime;
use DateTimeInterface;

/**
 * The Calendar class represents the details of a calendar entry
 * related to a transaction.
 *
 * It includes fields for the expiration period and created
 * date, allowing for effective management of transaction timelines.
 */
class Calendar
{
    private ?int $expiration;
    private ?DateTime $creation_date;

    public function __construct(
        ?int $expiration = null,
        ?DateTime $creation_date = null
    ) {
        $this->expiration = $expiration;
        $this->creation_date = $creation_date;
    }

    /**
     * @throws DateMalformedStringException
     */
    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['expiracao'] ?? null,
            isset($data['criacao']) ? new DateTime($data['criacao']) : null
        );
    }

    public function toArray(): array
    {
        return [
            "expiracao" => $this->expiration,
            "criacao" => $this->creation_date?->format(DateTimeInterface::ISO8601)
        ];
    }
}