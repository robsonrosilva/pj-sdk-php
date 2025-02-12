<?php

namespace Inter\Sdk\sdkLibrary\banking\enums;

/**
 * The PixStatus enum represents the various states a PIX transaction can be in.
 */
enum PixStatus: string
{
    case CRIADO = 'CRIADO';
    case AGUARDANDO_APROVACAO = 'AGUARDANDO_APROVACAO';
    case APROVADO = 'APROVADO';
    case REPROVADO = 'REPROVADO';
    case EXPIRADO = 'EXPIRADO';
    case CANCELADO = 'CANCELADO';
    case FALHA = 'FALHA';
    case AGENDADO = 'AGENDADO';
    case PAGO = 'PAGO';
    case ENVIADO = 'ENVIADO';
    case CANCELADO_SEM_SALDO = 'CANCELADO_SEM_SALDO';
    case DEBITADO = 'DEBITADO';
    case PARCIALMENTE_DEBITADO = 'PARCIALMENTE_DEBITADO';
    case PARCIALMENTE_PAGO = 'PARCIALMENTE_PAGO';
    case NAO_DEBITADO = 'NAO_DEBITADO';
    case AGENDAMENTO_CANCELADO = 'AGENDAMENTO_CANCELADO';
    case TRANSACAO_CRIADA = 'TRANSACAO_CRIADA';
    case TRANSACAO_APROVADA = 'TRANSACAO_APROVADA';
    case PIX_ENVIADO = 'PIX_ENVIADO';
    case PIX_PAGO = 'PIX_PAGO';

    /**
     * Create a PixStatus instance from a string value.
     *
     * @param string $value The string representation of the PixStatus.
     * @return PixStatus The corresponding PixStatus enum value.
     * @throws \ValueError If the input string doesn't match any PixStatus value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        foreach (self::cases() as $case) {
            if ($case->value === $upperValue) {
                return $case;
            }
        }

        throw new \ValueError("'{$value}' is not a valid PixStatus value");
    }

    /**
     * Get a human-readable description of the PIX status.
     *
     * @return string A description of the PIX status.
     */
    public function description(): string
    {
        return match($this) {
            self::CRIADO => 'Created',
            self::AGUARDANDO_APROVACAO => 'Awaiting Approval',
            self::APROVADO => 'Approved',
            self::REPROVADO => 'Rejected',
            self::EXPIRADO => 'Expired',
            self::CANCELADO => 'Cancelled',
            self::FALHA => 'Failed',
            self::AGENDADO => 'Scheduled',
            self::PAGO => 'Paid',
            self::ENVIADO => 'Sent',
            self::CANCELADO_SEM_SALDO => 'Cancelled due to Insufficient Balance',
            self::DEBITADO => 'Debited',
            self::PARCIALMENTE_DEBITADO => 'Partially Debited',
            self::PARCIALMENTE_PAGO => 'Partially Paid',
            self::NAO_DEBITADO => 'Not Debited',
            self::AGENDAMENTO_CANCELADO => 'Scheduled Payment Cancelled',
            self::TRANSACAO_CRIADA => 'Transaction Created',
            self::TRANSACAO_APROVADA => 'Transaction Approved',
            self::PIX_ENVIADO => 'PIX Sent',
            self::PIX_PAGO => 'PIX Paid',
        };
    }
}