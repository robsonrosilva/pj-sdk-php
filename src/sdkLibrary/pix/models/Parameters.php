<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The Parameters class represents a collection of parameters
 * including pagination details. It supports additional custom fields
 * via a map of additional attributes.
 */
class Parameters
{
    private ?string $begin;
    private ?string $end;
    private ?string $cpf;
    private ?string $cnpj;
    private ?bool $location_present;
    private ?string $status;
    private ?Pagination $pagination;
    private ?string $cob_type;

    public function getBegin(): ?string
    {
        return $this->begin;
    }

    public function getEnd(): ?string
    {
        return $this->end;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function getLocationPresent(): ?bool
    {
        return $this->location_present;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getPagination(): ?Pagination
    {
        return $this->pagination;
    }

    public function getCobType(): ?string
    {
        return $this->cob_type;
    }

    public function __construct(
        ?string $begin = null,
        ?string $end = null,
        ?string $cpf = null,
        ?string $cnpj = null,
        ?bool $location_present = null,
        ?string $status = null,
        ?Pagination $pagination = null,
        ?string $cob_type = null
    ) {
        $this->begin = $begin;
        $this->end = $end;
        $this->cpf = $cpf;
        $this->cnpj = $cnpj;
        $this->location_present = $location_present;
        $this->status = $status;
        $this->pagination = $pagination;
        $this->cob_type = $cob_type;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['inicio'] ?? null,
            $data['fim'] ?? null,
            $data['cpf'] ?? null,
            $data['cnpj'] ?? null,
            $data['locationPresente'] ?? null,
            $data['status'] ?? null,
            isset($data['paginacao']) ? Pagination::fromJson($data['paginacao']) : null,
            $data['tipoCob'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "inicio" => $this->begin,
            "fim" => $this->end,
            "cpf" => $this->cpf,
            "cnpj" => $this->cnpj,
            "locationPresente" => $this->location_present,
            "status" => $this->status,
            "paginacao" => $this->pagination?->toArray(),
            "tipoCob" => $this->cob_type
        ];
    }
}