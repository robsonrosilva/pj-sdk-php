<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The PixValue class represents the amount involved in a
 * transaction. It includes the original value, modification modality,
 * and withdrawal transaction details.
 */
class PixValue
{
    private ?string $original;
    private ?int $modification_modality;
    private ?WithdrawalTransaction $withdrawal_transaction;

    public function __construct(
        ?string $original = null,
        ?int $modification_modality = null,
        ?WithdrawalTransaction $withdrawal_transaction = null
    ) {
        $this->original = $original;
        $this->modification_modality = $modification_modality;
        $this->withdrawal_transaction = $withdrawal_transaction;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['original'] ?? null,
            $data['modalidadeAlteracao'] ?? null,
            isset($data['retirada']) ? WithdrawalTransaction::fromJson($data['retirada']) : null
        );
    }

    public function toArray(): array
    {
        return [
            "original" => $this->original,
            "modalidadeAlteracao" => $this->modification_modality,
            "retirada" => $this->withdrawal_transaction?->toArray()
        ];
    }
}