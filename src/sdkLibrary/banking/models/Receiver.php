<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The Receiver class represents the recipient of a PIX transaction,
 * including their bank details and identification.
 */
class Receiver
{
    private ?string $agency_code;
    private ?string $ispb_code;
    private ?string $cpf_or_cnpj;
    private ?string $name;
    private ?string $account_number;
    private ?string $account_type;

    public function __construct(
        ?string $agency_code = null,
        ?string $ispb_code = null,
        ?string $cpf_or_cnpj = null,
        ?string $name = null,
        ?string $account_number = null,
        ?string $account_type = null
    ) {
        $this->agency_code = $agency_code;
        $this->ispb_code = $ispb_code;
        $this->cpf_or_cnpj = $cpf_or_cnpj;
        $this->name = $name;
        $this->account_number = $account_number;
        $this->account_type = $account_type;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['codAgencia'] ?? null,
            $json['codIspb'] ?? null,
            $json['cpfCnpj'] ?? null,
            $json['nome'] ?? null,
            $json['nroConta'] ?? null,
            $json['tipoConta'] ?? null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "codAgencia" => $this->agency_code,
            "codIspb" => $this->ispb_code,
            "cpfCnpj" => $this->cpf_or_cnpj,
            "nome" => $this->name,
            "nroConta" => $this->account_number,
            "tipoConta" => $this->account_type
        ];
    }
}