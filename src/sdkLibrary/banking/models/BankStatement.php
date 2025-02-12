<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The BankStatement class represents a summary of transactions
 * for a specified bank account within a certain period.
 */
class BankStatement
{
    /**
     * A list of transactions included in the bank statement.
     *
     * @var Transaction[]|null
     */
    private ?array $transactions;

    /**
     * Constructs a new BankStatement with specified values.
     *
     * @param Transaction[]|null $transactions
     */
    public function __construct(?array $transactions = null)
    {
        $this->transactions = $transactions;
    }

    /**
     * Creates a BankStatement instance from a JSON string.
     *
     * @param mixed $json The JSON string containing the bank statement data.
     * @return BankStatement An instance of BankStatement.
     */
    public static function fromJson(mixed $json): self
    {
        return new self(
            array_map(
                fn($item) => Transaction::fromJson($item),
                $json['transacoes'] ?? []
            )
        );
    }

    /**
     * Convert the BankStatement instance to a JSON string.
     *
     * @throws JsonException
     */
    public function toArray(): array
    {
        return [
            'transacoes' => array_map(
                fn($transaction) => $transaction->toJson(),
                $this->transactions ?? []
            )
        ];
    }

    // Getters e Setters (opcional, se necessário)

    public function getTransactions(): ?array
    {
        return $this->transactions;
    }

    public function setTransactions(?array $transactions): void
    {
        $this->transactions = $transactions;
    }
}