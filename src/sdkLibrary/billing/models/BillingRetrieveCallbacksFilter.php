<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

/**
 * The BillingRetrieveCallbacksFilter class represents the filter criteria
 * used for searching callbacks.
 *
 * It contains a field for the request code that can be utilized to
 * uniquely identify and retrieve specific callback records. This structure is
 * essential for facilitating searches in callback retrieval processes.
 */
class BillingRetrieveCallbacksFilter
{
    private ?string $request_code;

    public function getRequestCode(): ?string
    {
        return $this->request_code;
    }

    public function setRequestCode(?string $request_code): void
    {
        $this->request_code = $request_code;
    }

    public function __construct(?string $request_code = null)
    {
        $this->request_code = $request_code;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            $json['codigoSolicitacao'] ?? null
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "codigoSolicitacao" => $this->request_code
        ];
    }
}