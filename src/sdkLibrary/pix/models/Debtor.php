<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The Debtor class represents information about a debtor in
 * a billing system.
 *
 * It includes fields such as CPF (Brazilian individual
 * taxpayer identification number), CNPJ (Brazilian corporate taxpayer
 * identification number), name, email, city, state (UF), postal code (CEP),
 * and address (logradouro).
 */
class Debtor
{
    private ?string $cpf;
    private ?string $cnpj;
    private ?string $name;
    private ?string $email;
    private ?string $city;
    private ?string $state;
    private ?string $postal_code;
    private ?string $address;

    public function __construct(
        ?string $cpf = null,
        ?string $cnpj = null,
        ?string $name = null,
        ?string $email = null,
        ?string $city = null,
        ?string $state = null,
        ?string $postal_code = null,
        ?string $address = null
    ) {
        $this->cpf = $cpf;
        $this->cnpj = $cnpj;
        $this->name = $name;
        $this->email = $email;
        $this->city = $city;
        $this->state = $state;
        $this->postal_code = $postal_code;
        $this->address = $address;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['cpf'] ?? null,
            $data['cnpj'] ?? null,
            $data['nome'] ?? null,
            $data['email'] ?? null,
            $data['cidade'] ?? null,
            $data['uf'] ?? null,
            $data['cep'] ?? null,
            $data['logradouro'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "cpf" => $this->cpf,
            "cnpj" => $this->cnpj,
            "nome" => $this->name,
            "email" => $this->email,
            "cidade" => $this->city,
            "uf" => $this->state,
            "cep" => $this->postal_code,
            "logradouro" => $this->address
        ];
    }
}