<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The WithdrawalTransaction class represents details of a
 * withdrawal operation. It includes fields for the withdrawal
 * details (Withdrawal) and any change returned (Change).
 */
class WithdrawalTransaction
{
    private ?Withdrawal $withdrawal;
    private ?Change $change;

    public function __construct(
        ?Withdrawal $withdrawal = null,
        ?Change $change = null
    ) {
        $this->withdrawal = $withdrawal;
        $this->change = $change;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            isset($data['saque']) ? Withdrawal::fromJson($data['saque']) : null,
            isset($data['troco']) ? Change::fromJson($data['troco']) : null
        );
    }

    public function toArray(): array
    {
        return [
            "saque" => $this->withdrawal?->toArray(),
            "troco" => $this->change?->toArray()
        ];
    }
}