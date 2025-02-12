<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

/**
 * The CancelBillingRequest class represents a request to cancel a billing.
 *
 * This class includes the reason for cancellation and allows for the
 * inclusion of additional fields that may be required by the specific use case.
 */
class CancelBillingRequest
{
    private ?string $cancellation_reason;

    public function __construct(?string $cancellation_reason = null)
    {
        $this->cancellation_reason = $cancellation_reason;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            $json['cancellation_reason'] ?? null
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "motivoCancelamento" => $this->cancellation_reason
        ];
    }
}