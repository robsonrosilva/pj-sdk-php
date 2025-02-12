<?php

namespace Inter\Sdk\sdkLibrary\billing\models;


/**
 * The BillingRetrieveCallbackResponse class represents the response structure
 * for retrieving callback information.
 *
 * It includes details such as the URL of the webhook, the number of
 * attempts made to trigger the callback, the timestamp of the last trigger,
 * and the success status of the callback. Additionally, it may contain the
 * HTTP status, error message, and a list of payloads related to the callback.
 * This structure is essential for managing and responding to callback inquiries.
 */
class BillingRetrieveCallbackResponse
{
    private ?string $webhook_url;
    private ?int $attempt_number;
    private ?string $trigger_date_time;
    private ?bool $success;
    private ?int $http_status;
    private ?string $error_message;
    private array $payload; // List of BillingPayload objects

    public function __construct(
        ?string $webhook_url = null,
        ?int $attempt_number = null,
        ?string $trigger_date_time = null,
        ?bool $success = null,
        ?int $http_status = null,
        ?string $error_message = null,
        ?array $payload = null
    ) {
        $this->webhook_url = $webhook_url;
        $this->attempt_number = $attempt_number;
        $this->trigger_date_time = $trigger_date_time;
        $this->success = $success;
        $this->http_status = $http_status;
        $this->error_message = $error_message;
        $this->payload = $payload;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            $json['webhookUrl'] ?? null,
            $json['numeroTentativa'] ?? null,
            $json['dataHoraDisparo'] ?? null,
            $json['sucesso'] ?? null,
            $json['httpStatus'] ?? null,
            $json['mensagemErro'] ?? null,
            array_map(fn($item) => BillingPayload::fromJson($item), $json['payload'] ?? [])
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "webhookUrl" => $this->webhook_url,
            "numeroTentativa" => $this->attempt_number,
            "dataHoraDisparo" => $this->trigger_date_time,
            "sucesso" => $this->success,
            "httpStatus" => $this->http_status,
            "mensagemErro" => $this->error_message,
            "payload" => array_map(fn($item) => $item->toArray(), $this->payload)
        ];
    }
}