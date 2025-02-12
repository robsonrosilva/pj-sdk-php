<?php
namespace Inter\Sdk\sdkLibrary\banking\models;
use JsonException;

/**
 * The Payload class represents the details of a payment payload,
 * including transaction info, beneficiary details, and any associated errors.
 */
class Payload
{
    private ?string $transaction_code;
    private ?string $digitable_line;
    private ?string $movement_date_time;
    private ?string $request_date_time;
    private ?string $beneficiary_name;
    private ?string $scheduled_amount;
    private ?string $paid_value;
    private ?string $end_to_end_id;
    private ?Receiver $receiver;
    private ?string $status;
    private ?string $movement_type;
    private ?string $amount;
    private array $errors; // List of CallbackError objects
    private ?string $payment_date;
    public function __construct(
        ?string $transaction_code = null,
        ?string $digitable_line = null,
        ?string   $movement_date_time = null,
        ?string   $request_date_time = null,
        ?string   $beneficiary_name = null,
        ?string   $scheduled_amount = null,
        ?string   $paid_value = null,
        ?string   $end_to_end_id = null,
        ?Receiver $receiver = null,
        ?string   $status = null,
        ?string   $movement_type = null,
        ?string   $amount = null,
        ?array    $errors = null,
        ?string   $payment_date = null
    ) {
        $this->transaction_code = $transaction_code;
        $this->digitable_line = $digitable_line;
        $this->movement_date_time = $movement_date_time;
        $this->request_date_time = $request_date_time;
        $this->beneficiary_name = $beneficiary_name;
        $this->scheduled_amount = $scheduled_amount;
        $this->paid_value = $paid_value;
        $this->end_to_end_id = $end_to_end_id;
        $this->receiver = $receiver;
        $this->status = $status;
        $this->movement_type = $movement_type;
        $this->amount = $amount;
        $this->errors = $errors; // List of CallbackError objects
        $this->payment_date = $payment_date;
    }
    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['codigoTransacao'] ?? null,
            $json['linhaDigitavel'] ?? null,
            $json['dataHoraMovimento'] ?? null,
            $json['dataHoraSolicitacao'] ?? null,
            $json['nomeBeneficiario'] ?? null,
            $json['valorAgendado'] ?? null,
            $json['valorPago'] ?? null,
            $json['endToEnd'] ?? null,
            isset($json['recebedor']) ? Receiver::fromJson($json['recebedor']) : null,
            $json['status'] ?? null,
            $json['tipoMovimentacao'] ?? null,
            $json['valor'] ?? null,
            array_map(fn($error) => CallbackError::fromJson($error), $json['erros'] ?? []),
            $json['dataPagamento'] ?? null
        );
    }
    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "codigoTransacao" => $this->transaction_code,
            "linhaDigitavel" => $this->digitable_line,
            "dataHoraMovimento" => $this->movement_date_time,
            "dataHoraSolicitacao" => $this->request_date_time,
            "nomeBeneficiario" => $this->beneficiary_name,
            "valorAgendado" => $this->scheduled_amount,
            "valorPago" => $this->paid_value,
            "endToEnd" => $this->end_to_end_id,
            "recebedor" => $this->receiver ? $this->receiver->toJson() : null,
            "status" => $this->status,
            "tipoMovimentacao" => $this->movement_type,
            "valor" => $this->amount,
            "erros" => array_map(fn($error) => $error->toJson(), $this->errors),
            "dataPagamento" => $this->payment_date,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }
    public function toArray(): array
    {
        return [
            "codigoTransacao" => $this->transaction_code,
            "linhaDigitavel" => $this->digitable_line,
            "dataHoraMovimento" => $this->movement_date_time,
            "dataHoraSolicitacao" => $this->request_date_time,
            "nomeBeneficiario" => $this->beneficiary_name,
            "valorAgendado" => $this->scheduled_amount,
            "valorPago" => $this->paid_value,
            "endToEnd" => $this->end_to_end_id,
            "recebedor" => $this->receiver ? $this->receiver->toArray() : null,
            "status" => $this->status,
            "tipoMovimentacao" => $this->movement_type,
            "valor" => $this->amount,
            "erros" => array_map(fn($error) => $error->toArray(), $this->errors),
            "dataPagamento" => $this->payment_date,
        ];
    }
}