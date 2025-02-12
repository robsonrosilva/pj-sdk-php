<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The Problem class represents an error or problem encountered
 * during a PIX transaction. It includes various fields detailing the
 * nature of the problem, including its type, title, status, and any
 * relevant violations.
 */
class Problem
{
    private ?string $type;
    private ?string $title;
    private ?int $status;
    private ?string $detail;
    private ?string $correlation_id;
    private array $violations;

    public function __construct(
        ?string $type = null,
        ?string $title = null,
        ?int $status = null,
        ?string $detail = null,
        ?string $correlation_id = null,
        array $violations = []
    ) {
        $this->type = $type;
        $this->title = $title;
        $this->status = $status;
        $this->detail = $detail;
        $this->correlation_id = $correlation_id;
        $this->violations = $violations;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['type'] ?? null,
            $data['title'] ?? null,
            $data['status'] ?? null,
            $data['detail'] ?? null,
            $data['correlationId'] ?? null,
            array_map(fn($v) => Violation::fromJson($v), $data['violacoes'] ?? [])
        );
    }

    public function toArray(): array
    {
        return [
            "type" => $this->type,
            "title" => $this->title,
            "status" => $this->status,
            "detail" => $this->detail,
            "correlationId" => $this->correlation_id,
            "violacoes" => array_map(fn($v) => $v->toArray(), $this->violations)
        ];
    }
}