<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

/**
 * The Message class represents a customizable message that can be
 * displayed to users, consisting of up to five lines of text.
 *
 * It is used to map data from a JSON structure, allowing the
 * deserialization of received information for user notifications or alerts.
 */
class Message
{
    private ?string $line1;
    private ?string $line2;
    private ?string $line3;
    private ?string $line4;
    private ?string $line5;

    public function __construct(
        ?string $line1 = null,
        ?string $line2 = null,
        ?string $line3 = null,
        ?string $line4 = null,
        ?string $line5 = null
    ) {
        $this->line1 = $line1;
        $this->line2 = $line2;
        $this->line3 = $line3;
        $this->line4 = $line4;
        $this->line5 = $line5;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            $json['linha1'] ?? null,
            $json['linha2'] ?? null,
            $json['linha3'] ?? null,
            $json['linha4'] ?? null,
            $json['linha5'] ?? null
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "linha1" => $this->line1,
            "linha2" => $this->line2,
            "linha3" => $this->line3,
            "linha4" => $this->line4,
            "linha5" => $this->line5
        ];
    }
}