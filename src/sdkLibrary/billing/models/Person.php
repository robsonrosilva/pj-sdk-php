<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

use Inter\Sdk\sdkLibrary\billing\enums\PersonType;

/**
 * The Person class represents an individual's or company's information,
 * including identification details, contact information, and address.
 *
 * It is used to map data from a JSON structure, enabling the
 * deserialization of received information. This class encapsulates essential
 * attributes necessary for identifying and contacting a person or entity.
 */
class Person
{
    private ?string $cpf_cnpj;
    private ?PersonType $person_type;
    private ?string $name;
    private ?string $address;
    private ?string $number;
    private ?string $complement;
    private ?string $neighborhood;
    private ?string $city;
    private ?string $state;
    private ?string $zip_code;
    private ?string $email;
    private ?string $area_code;
    private ?string $phone;

    public function __construct(
        ?string $cpf_cnpj = null,
        ?PersonType $person_type = null,
        ?string $name = null,
        ?string $address = null,
        ?string $number = null,
        ?string $complement = null,
        ?string $neighborhood = null,
        ?string $city = null,
        ?string $state = null,
        ?string $zip_code = null,
        ?string $email = null,
        ?string $area_code = null,
        ?string $phone = null
    ) {
        $this->cpf_cnpj = $cpf_cnpj;
        $this->person_type = $person_type;
        $this->name = $name;
        $this->address = $address;
        $this->number = $number;
        $this->complement = $complement;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->state = $state;
        $this->zip_code = $zip_code;
        $this->email = $email;
        $this->area_code = $area_code;
        $this->phone = $phone;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            $json['cpfCnpj'] ?? null,
            isset($json['tipoPessoa']) ? PersonType::fromString($json['tipoPessoa']) : null,
            $json['nome'] ?? null,
            $json['endereco'] ?? null,
            $json['numero'] ?? null,
            $json['complemento'] ?? null,
            $json['bairro'] ?? null,
            $json['cidade'] ?? null,
            $json['uf'] ?? null,
            $json['cep'] ?? null,
            $json['email'] ?? null,
            $json['ddd'] ?? null,
            $json['telefone'] ?? null
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "cpfCnpj" => $this->cpf_cnpj,
            "tipoPessoa" => $this->person_type?->value,
            "nome" => $this->name,
            "endereco" => $this->address,
            "numero" => $this->number,
            "complemento" => $this->complement,
            "bairro" => $this->neighborhood,
            "cidade" => $this->city,
            "uf" => $this->state,
            "cep" => $this->zip_code,
            "email" => $this->email,
            "ddd" => $this->area_code,
            "telefone" => $this->phone
        ];
    }
}