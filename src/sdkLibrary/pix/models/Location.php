<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use DateMalformedStringException;
use DateTime;
use DateTimeInterface;
use Inter\Sdk\sdkLibrary\pix\enums\ImmediateBillingType;

/**
 * The Location class represents information about a payment location
 * in a billing system. It includes fields such as the type of billing
 * (CobType), a unique identifier for the location, the actual location
 * value, and the creation date of the location entry. Additionally, it
 * allows for the inclusion of any extra fields through a map for dynamic
 * attributes that may not be predefined. This structure is essential for
 * managing payment locations in the context of financial transactions.
 */
class Location
{
    private ?ImmediateBillingType $billing_type;
    private ?int $id;
    private ?string $location;
    private ?DateTime $creation_date;
    private ?string $txid;

    public function __construct(
        ?ImmediateBillingType $billing_type = null,
        ?int $id = null,
        ?string $location = null,
        ?DateTime $creation_date = null,
        ?string $txid = null
    ) {
        $this->billing_type = $billing_type;
        $this->id = $id;
        $this->location = $location;
        $this->creation_date = $creation_date;
        $this->txid = $txid;
    }

    /**
     * @throws DateMalformedStringException
     */
    public static function fromJson(mixed $data): self
    {
        return new self(
            isset($data['tipoCob']) ? ImmediateBillingType::fromString($data['tipoCob']) : null,
            $data['id'] ?? null,
            $data['location'] ?? null,
            isset($data['criacao']) ? new DateTime($data['criacao']) : null,
            $data['txid'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "tipoCob" => $this->billing_type?->value,
            "id" => $this->id,
            "location" => $this->location,
            "criacao" => $this->creation_date?->format(DateTimeInterface::ATOM),
            "txid" => $this->txid
        ];
    }
}