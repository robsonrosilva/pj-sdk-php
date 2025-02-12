<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use Inter\Sdk\sdkLibrary\banking\enums\PixStatus;
use JsonException;

/**
 * The PixTransaction class represents a PIX transaction,
 * including details such as account information, receiver, errors,
 * transaction status, and timestamps.
 */
class PixTransaction
{
    private ?string $account;
    private ?Receiver $receiver;
    private array $errors; // List of PixTransactionError objects
    private ?string $end_to_end;
    private ?int $value;
    private ?PixStatus $status;
    private ?string $movement_date_time;
    private ?string $request_date_time;
    private ?string $key;
    private ?string $request_code;

    public function __construct(
        ?string    $account = null,
        ?Receiver  $receiver = null,
        ?array     $errors = null,
        ?string    $end_to_end = null,
        ?int       $value = null,
        ?PixStatus $status = null,
        ?string    $movement_date_time = null,
        ?string    $request_date_time = null,
        ?string    $key = null,
        ?string    $request_code = null
    ) {
        $this->account = $account;
        $this->receiver = $receiver;
        $this->errors = $errors;
        $this->end_to_end = $end_to_end;
        $this->value = $value;
        $this->status = $status;
        $this->movement_date_time = $movement_date_time;
        $this->request_date_time = $request_date_time;
        $this->key = $key;
        $this->request_code = $request_code;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['contaCorrente'] ?? null,
            isset($json['recebedor']) ? Receiver::fromJson($json['recebedor']) : null,
            array_map(fn($error) => PixTransactionError::fromJson($error), $json['erros'] ?? []),
            $json['endToEnd'] ?? null,
            $json['valor'] ?? null,
            isset($json['status']) ? PixStatus::fromString($json['status']) : null,
            $json['dataHoraMovimento'] ?? null,
            $json['dataHoraSolicitacao'] ?? null,
            $json['chave'] ?? null,
            $json['codigoSolicitacao'] ?? null
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
            "contaCorrente" => $this->account,
            "recebedor" => $this->receiver?->toArray(),
            "erros" => array_map(fn($error) => $error->toArray(), $this->errors),
            "endToEnd" => $this->end_to_end,
            "valor" => $this->value,
            "status" => $this->status?->value,
            "dataHoraMovimento" => $this->movement_date_time,
            "dataHoraSolicitacao" => $this->request_date_time,
            "chave" => $this->key,
            "codigoSolicitacao" => $this->request_code
        ];
    }
}