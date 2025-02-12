<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use DateMalformedStringException;
use DateTime;
use DateTimeInterface;

/**
 * The CobMoment class represents the moments associated
 * with a charge, specifically the request and liquidation dates.
 *
 * This class provides a structure for holding important
 * timestamps related to the charging process.
 */
class CobMoment
{
    private ?DateTime $request;
    private ?DateTime $liquidation;

    public function __construct(
        ?DateTime $request = null,
        ?DateTime $liquidation = null
    ) {
        $this->request = $request;
        $this->liquidation = $liquidation;
    }

    /**
     * @throws DateMalformedStringException
     */
    public static function fromJson(mixed $data): self
    {
        return new self(
            isset($data['solicitacao']) ? new DateTime($data['solicitacao']) : null,
            isset($data['liquidacao']) ? new DateTime($data['liquidacao']) : null
        );
    }

    public function toArray(): array
    {
        return [
            "solicitacao" => $this->request?->format(DateTimeInterface::ISO8601),
            "liquidacao" => $this->liquidation?->format(DateTimeInterface::ISO8601)
        ];
    }
}