<?php

namespace Inter\Sdk\sdkLibrary\banking\enums;

/**
 * The TransactionType enum represents the various types of financial transactions.
 */
enum TransactionType: string
{
    /**
     * PIX instant payment
     */
    case PIX = 'PIX';

    /**
     * Foreign exchange
     */
    case CAMBIO = 'CAMBIO';

    /**
     * Refund or chargeback
     */
    case ESTORNO = 'ESTORNO';

    /**
     * Investment
     */
    case INVESTIMENTO = 'INVESTIMENTO';

    /**
     * Transfer
     */
    case TRANSFERENCIA = 'TRANSFERENCIA';

    /**
     * Payment
     */
    case PAGAMENTO = 'PAGAMENTO';

    /**
     * Payment slip (boleto)
     */
    case BOLETO_COBRANCA = 'BOLETO_COBRANCA';

    /**
     * Other types of transactions
     */
    case OUTROS = 'OUTROS';

    /**
     * Create a TransactionType instance from a string value.
     *
     * @param string $value The string representation of the TransactionType.
     * @return TransactionType The corresponding TransactionType enum value.
     * @throws \ValueError If the input string doesn't match any TransactionType value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        foreach (self::cases() as $case) {
            if ($case->value === $upperValue) {
                return $case;
            }
        }

        throw new \ValueError("'{$value}' is not a valid TransactionType value");
    }

    /**
     * Get a human-readable description of the transaction type.
     *
     * @return string A description of the transaction type.
     */
    public function description(): string
    {
        return match($this) {
            self::PIX => 'PIX Instant Payment',
            self::CAMBIO => 'Foreign Exchange',
            self::ESTORNO => 'Refund/Chargeback',
            self::INVESTIMENTO => 'Investment',
            self::TRANSFERENCIA => 'Transfer',
            self::PAGAMENTO => 'Payment',
            self::BOLETO_COBRANCA => 'Payment Slip (Boleto)',
            self::OUTROS => 'Other',
        };
    }
}