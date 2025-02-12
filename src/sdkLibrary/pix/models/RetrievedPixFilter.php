<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The RetrievedPixFilter class is used to filter received
 * PIX transactions based on various criteria. It includes fields
 * for transaction ID (txId), presence of transaction ID, presence
 * of return, and identification numbers such as CPF and CNPJ.
 */
class RetrievedPixFilter
{
    private ?string $tx_id;
    private ?bool $tx_id_present;
    private ?bool $devolution_present;
    private ?string $cpf;
    private ?string $cnpj;

    public function __construct(
        ?string $tx_id = null,
        ?bool $tx_id_present = null,
        ?bool $devolution_present = null,
        ?string $cpf = null,
        ?string $cnpj = null
    ) {
        $this->tx_id = $tx_id;
        $this->tx_id_present = $tx_id_present;
        $this->devolution_present = $devolution_present;
        $this->cpf = $cpf;
        $this->cnpj = $cnpj;
    }

    public function getTxId(): ?string
    {
        return $this->tx_id;
    }

    public function setTxId(?string $tx_id): void
    {
        $this->tx_id = $tx_id;
    }

    public function getTxIdPresent(): ?bool
    {
        return $this->tx_id_present;
    }

    public function setTxIdPresent(?bool $tx_id_present): void
    {
        $this->tx_id_present = $tx_id_present;
    }

    public function getDevolutionPresent(): ?bool
    {
        return $this->devolution_present;
    }

    public function setDevolutionPresent(?bool $devolution_present): void
    {
        $this->devolution_present = $devolution_present;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(?string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function setCnpj(?string $cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['txId'] ?? null,
            $data['txIdPresente'] ?? null,
            $data['devolucaoPresente'] ?? null,
            $data['cpf'] ?? null,
            $data['cnpj'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            "txId" => $this->tx_id,
            "txIdPresente" => $this->tx_id_present,
            "devolucaoPresente" => $this->devolution_present,
            "cpf" => $this->cpf,
            "cnpj" => $this->cnpj
        ];
    }
}