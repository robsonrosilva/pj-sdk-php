<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The IncludePixResponse class represents the response for including a PIX payment,
 * including details like return type, end-to-end ID, request code, payment date, scheduling code,
 * operation date, and payment hour.
 */
class IncludePixResponse
{
    private ?string $return_type;
    private ?string $end_to_end_id;
    private ?string $request_code;
    private ?string $payment_date;
    private ?string $scheduling_code;
    private ?string $operation_date;
    private ?string $payment_hour;

    public function __construct(
        ?string $return_type = null,
        ?string $end_to_end_id = null,
        ?string $request_code = null,
        ?string $payment_date = null,
        ?string $scheduling_code = null,
        ?string $operation_date = null,
        ?string $payment_hour = null
    ) {
        $this->return_type = $return_type;
        $this->end_to_end_id = $end_to_end_id;
        $this->request_code = $request_code;
        $this->payment_date = $payment_date;
        $this->scheduling_code = $scheduling_code;
        $this->operation_date = $operation_date;
        $this->payment_hour = $payment_hour;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['tipoRetorno'] ?? null,
            $json['endToEndId'] ?? null,
            $json['codigoSolicitacao'] ?? null,
            $json['dataPagamento'] ?? null,
            $json['codigoAgendamento'] ?? null,
            $json['dataOperacao'] ?? null,
            $json['horaPagamento'] ?? null
        );
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        $obj = [
            "tipoRetorno" => $this->return_type,
            "endToEndId" => $this->end_to_end_id,
            "codigoSolicitacao" => $this->request_code,
            "dataPagamento" => $this->payment_date,
            "codigoAgendamento" => $this->scheduling_code,
            "dataOperacao" => $this->operation_date,
            "horaPagamento" => $this->payment_hour,
        ];
        return json_encode($obj, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function toArray(): array
    {
        return [
            "tipoRetorno" => $this->return_type,
            "endToEndId" => $this->end_to_end_id,
            "codigoSolicitacao" => $this->request_code,
            "dataPagamento" => $this->payment_date,
            "codigoAgendamento" => $this->scheduling_code,
            "dataOperacao" => $this->operation_date,
            "horaPagamento" => $this->payment_hour,
        ];
    }
}