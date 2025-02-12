<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use DateTime;
use DateTimeInterface;

/**
 * The DueBillingCalendar class represents the calendar details
 * related to a billing transaction.
 *
 * It includes fields for the creation date, validity period
 * after expiration, and the due date. This structure is essential for
 * managing the timing and validity of billing processes.
 */
class DueBillingCalendar
{
    private ?DateTime $creation_date;
    private ?int $validity_after_expiration;
    private ?string $due_date;

    public function __construct(
        ?DateTime $creation_date = null,
        ?int $validity_after_expiration = null,
        ?string $due_date = null
    ) {
        $this->creation_date = $creation_date;
        $this->validity_after_expiration = $validity_after_expiration;
        $this->due_date = $due_date;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function fromJson(mixed $data): self
    {
        return new self(
            isset($data['criacao']) ? new DateTime($data['criacao']) : null,
            $data['validadeAposVencimento'] ?? null,
            $data['dataDeVencimento'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "criacao" => $this->creation_date?->format(DateTimeInterface::ISO8601),
            "validadeAposVencimento" => $this->validity_after_expiration,
            "dataDeVencimento" => $this->due_date
        ];
    }
}