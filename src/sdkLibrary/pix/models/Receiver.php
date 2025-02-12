<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The Receiver class represents the details of a recipient
 * involved in a transaction. It includes fields for the receiver's
 * name, CNPJ (Cadastro Nacional da Pessoa Jurídica), trade name,
 * city, state (UF), postal code (CEP), and address (logradouro).
 */
class Receiver
{
    private ?string $name;
    private ?string $cnpj;
    private ?string $trade_name;
    private ?string $city;
    private ?string $state;
    private ?string $postal_code;
    private ?string $address;

    public function __construct(
        ?string $name = null,
        ?string $cnpj = null,
        ?string $trade_name = null,
        ?string $city = null,
        ?string $state = null,
        ?string $postal_code = null,
        ?string $address = null
    ) {
        $this->name = $name;
        $this->cnpj = $cnpj;
        $this->trade_name = $trade_name;
        $this->city = $city;
        $this->state = $state;
        $this->postal_code = $postal_code;
        $this->address = $address;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['nome'] ?? null,
            $data['cnpj'] ?? null,
            $data['nomeFantasia'] ?? null,
            $data['cidade'] ?? null,
            $data['uf'] ?? null,
            $data['cep'] ?? null,
            $data['logradouro'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "nome" => $this->name,
            "cnpj" => $this->cnpj,
            "nomeFantasia" => $this->trade_name,
            "cidade" => $this->city,
            "uf" => $this->state,
            "cep" => $this->postal_code,
            "logradouro" => $this->address
        ];
    }
}