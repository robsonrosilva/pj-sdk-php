<?php

namespace Inter\Sdk\sdkLibrary\commons\models;

use JsonSerializable;

/**
 * The PdfReturn class represents the response object
 * that includes a PDF file in string format along with any
 * additional fields that may be required.
 */
class PdfReturn implements JsonSerializable
{
    /**
     * The PDF file represented as a Base64 encoded string.
     */
    private ?string $pdf = null;

    /**
     * Constructs a new PdfReturn.
     *
     * @param string|null $pdf The PDF file as a Base64 encoded string.
     */
    public function __construct(?string $pdf = null)
    {
        $this->pdf = $pdf;
    }

    /**
     * Get the PDF file.
     *
     * @return string|null The PDF file as a Base64 encoded string.
     */
    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    /**
     * Set the PDF file.
     *
     * @param string|null $pdf The PDF file as a Base64 encoded string.
     */
    public function setPdf(?string $pdf): void
    {
        $this->pdf = $pdf;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'pdf' => $this->pdf,
        ];
    }

    /**
     * Create a new PdfReturn instance using a builder pattern.
     *
     * @return PdfReturnBuilder
     */
    public static function builder(): PdfReturnBuilder
    {
        return new PdfReturnBuilder();
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            ($json['pdf']) ?? null
        );
    }
}

/**
 * Builder class for PdfReturn
 */
class PdfReturnBuilder
{
    private ?string $pdf = null;

    /**
     * Set the PDF file.
     *
     * @param string|null $pdf The PDF file as a Base64 encoded string.
     * @return self
     */
    public function pdf(?string $pdf): self
    {
        $this->pdf = $pdf;
        return $this;
    }

    /**
     * Build the PdfReturn instance.
     *
     * @return PdfReturn
     */
    public function build(): PdfReturn
    {
        return new PdfReturn($this->pdf);
    }
}
