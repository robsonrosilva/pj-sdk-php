<?php

namespace Inter\Sdk\sdkLibrary\commons\models;

use JsonSerializable;

/**
 * The Violation class represents a violation that occurred
 * during processing, providing details about the reason for the
 * violation, the property involved, and the value that was
 * rejected or erroneous.
 */
class Violation implements JsonSerializable
{
    /**
     * The reason for the violation.
     */
    private ?string $reason = null;

    /**
     * The property that is associated with the violation.
     */
    private ?string $property = null;

    /**
     * The value that was rejected or caused the violation.
     */
    private ?string $value = null;

    /**
     * Constructs a new Violation.
     *
     * @param string|null $reason The reason for the violation.
     * @param string|null $property The property associated with the violation.
     * @param string|null $value The value that was rejected or caused the violation.
     */
    public function __construct(?string $reason = null, ?string $property = null, ?string $value = null)
    {
        $this->reason = $reason;
        $this->property = $property;
        $this->value = $value;
    }

    /**
     * Get the reason for the violation.
     *
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * Set the reason for the violation.
     *
     * @param string|null $reason
     */
    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * Get the property associated with the violation.
     *
     * @return string|null
     */
    public function getProperty(): ?string
    {
        return $this->property;
    }

    /**
     * Set the property associated with the violation.
     *
     * @param string|null $property
     */
    public function setProperty(?string $property): void
    {
        $this->property = $property;
    }

    /**
     * Get the value that was rejected or caused the violation.
     *
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Set the value that was rejected or caused the violation.
     *
     * @param string|null $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'razao' => $this->reason,
            'propriedade' => $this->property,
            'valor' => $this->value,
        ];
    }

    /**
     * Create a new Violation instance using a builder pattern.
     *
     * @return ViolationBuilder
     */
    public static function builder(): ViolationBuilder
    {
        return new ViolationBuilder();
    }
}

/**
 * Builder class for Violation
 */
class ViolationBuilder
{
    private ?string $reason = null;
    private ?string $property = null;
    private ?string $value = null;

    /**
     * Set the reason for the violation.
     *
     * @param string|null $reason
     * @return self
     */
    public function reason(?string $reason): self
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * Set the property associated with the violation.
     *
     * @param string|null $property
     * @return self
     */
    public function property(?string $property): self
    {
        $this->property = $property;
        return $this;
    }

    /**
     * Set the value that was rejected or caused the violation.
     *
     * @param string|null $value
     * @return self
     */
    public function value(?string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Build the Violation instance.
     *
     * @return Violation
     */
    public function build(): Violation
    {
        return new Violation($this->reason, $this->property, $this->value);
    }
}
