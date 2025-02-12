<?php

namespace Inter\Sdk\sdkLibrary\commons\models;

use JsonSerializable;

/**
 * The Error class represents an error response object containing
 * details about an error that occurred during processing. It includes
 * a title, a detailed description, the timestamp of the error,
 * any violations associated with the error, and additional fields.
 */
class Error implements JsonSerializable
{
    /**
     * A brief title summarizing the type of error.
     */
    private ?string $title = null;

    /**
     * A detailed description of the error.
     */
    private ?string $detail = null;

    /**
     * The timestamp of when the error occurred.
     */
    private ?string $timestamp = null;

    /**
     * A list of violations that occurred during the processing.
     */
    private ?array $violations = null;

    public function __construct(?string $title = null, ?string $detail = null, ?string $timestamp = null, ?array $violations = null)
    {
        $this->title = $title;
        $this->detail = $detail;
        $this->timestamp = $timestamp;
        $this->violations = $violations;
    }

    // Getters
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    public function getViolations(): ?array
    {
        return $this->violations;
    }

    // Setters
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function setDetail(?string $detail): void
    {
        $this->detail = $detail;
    }

    public function setTimestamp(?string $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function setViolations(?array $violations): void
    {
        $this->violations = $violations;
    }

    /**
     * Specify data which should be serialized to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title,
            'detail' => $this->detail,
            'timestamp' => $this->timestamp,
            'violacoes' => $this->violations,
        ];
    }

    /**
     * Create a new Error instance using a builder pattern.
     */
    public static function builder(): ErrorBuilder
    {
        return new ErrorBuilder();
    }
}

/**
 * Builder class for Error
 */
class ErrorBuilder
{
    private ?string $title = null;
    private ?string $detail = null;
    private ?string $timestamp = null;
    private ?array $violations = null;

    public function title(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function detail(?string $detail): self
    {
        $this->detail = $detail;
        return $this;
    }

    public function timestamp(?string $timestamp): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function violations(?array $violations): self
    {
        $this->violations = $violations;
        return $this;
    }

    public function build(): Error
    {
        return new Error($this->title, $this->detail, $this->timestamp, $this->violations);
    }
}
