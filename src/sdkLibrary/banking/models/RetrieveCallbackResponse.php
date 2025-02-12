<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The RetrieveCallbackResponse class represents the response
 * received from a webhook callback for retrieving transaction data.
 */
class RetrieveCallbackResponse
{
    private ?string $webhook_url;
    private ?int $attempt_number;
    private ?string $sending_time;
    private ?string $trigger_date_time;
    private ?bool $success;
    private ?int $http_status;
    private ?string $error_message;
    private array $payload; // List of Payload objects

    public function __construct(
        ?string $webhook_url = null,
        ?int $attempt_number = null,
        ?string $sending_time = null,
        ?string $trigger_date_time = null,
        ?bool $success = null,
        ?int $http_status = null,
        ?string $error_message = null,
        ?array $payload = null
    ) {
        $this->webhook_url = $webhook_url;
        $this->attempt_number = $attempt_number;
        $this->sending_time = $sending_time;
        $this->trigger_date_time = $trigger_date_time;
        $this->success = $success;
        $this->http_status = $http_status;
        $this->error_message = $error_message;
        $this->payload = $payload;
    }

    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['webhookUrl'] ?? null,
            $json['numeroTentativa'] ?? null,
            $json['dataEnvio'] ?? null,
            $json['dataHoraDisparo'] ?? null,
            $json['sucesso'] ?? null,
            $json['httpStatus'] ?? null,
            $json['mensagemErro'] ?? null,
            array_map(fn($item) => Payload::fromJson($item), $json['payload'] ?? [])
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
            "webhookUrl" => $this->webhook_url,
            "numeroTentativa" => $this->attempt_number,
            "dataEnvio" => $this->sending_time,
            "dataHoraDisparo" => $this->trigger_date_time,
            "sucesso" => $this->success,
            "httpStatus" => $this->http_status,
            "mensagemErro" => $this->error_message,
            "payload" => array_map(fn($item) => $item->toArray(), $this->payload)
        ];
    }
}