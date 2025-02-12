<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The CopyAndPaste class represents a recipient using the Copy and Paste method,
 * including the copy-and-paste data and type.
 */
class CopyAndPaste extends Recipient
{
    private ?string $copy_and_paste;
    private ?string $_type_;

    public function __construct(
        ?string $copy_and_paste = null
    ) {
        $this->copy_and_paste = $copy_and_paste;
        $this->_type_ = "";
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['pixCopiaECola'] ?? null,
            $json['tipo'] ?? null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "pixCopiaECola" => $this->copy_and_paste,
            "tipo" => $this->_type_,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "pixCopiaECola" => $this->copy_and_paste,
            "tipo" => $this->_type_,
        ];
    }
}