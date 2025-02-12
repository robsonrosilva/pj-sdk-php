<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The RetrieveCallbackResponse class represents the response
 * received after attempting to retrieve callbacks. It includes
 * details such as the webhook URL, number of attempts,
 * timestamp of the trigger, success status, HTTP status,
 * error message, and associated payload.
 */
class RetrieveCallbackResponse
{
    private ?string $webhook_url;
    private ?int $attempt_number;
    private ?string $trigger_timestamp;
    private ?bool $success;
    private ?int $http_status;
    private ?string $error_message;
    private ?PixPayload $payload;

    public function __construct(
        ?string $webhook_url = null,
        ?int $attempt_number = null,
        ?string $trigger_timestamp = null,
        ?bool $success = null,
        ?int $http_status = null,
        ?string $error_message = null,
        ?PixPayload $payload = null
    ) {
        $this->webhook_url = $webhook_url;
        $this->attempt_number = $attempt_number;
        $this->trigger_timestamp = $trigger_timestamp;
        $this->success = $success;
        $this->http_status = $http_status;
        $this->error_message = $error_message;
        $this->payload = $payload;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['webhookUrl'] ?? null,
            $data['numeroTentativa'] ?? null,
            $data['dataHoraDisparo'] ?? null,
            $data['sucesso'] ?? null,
            $data['httpStatus'] ?? null,
            $data['mensagemErro'] ?? null,
            isset($data['payload']) ? PixPayload::fromJson($data['payload']) : null
        );
    }

    public function toArray(): array
    {
        return [
            "webhookUrl" => $this->webhook_url,
            "numeroTentativa" => $this->attempt_number,
            "dataHoraDisparo" => $this->trigger_timestamp,
            "sucesso" => $this->success,
            "httpStatus" => $this->http_status,
            "mensagemErro" => $this->error_message,
            "payload" => $this->payload?->toArray()
        ];
    }
}