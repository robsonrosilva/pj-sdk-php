<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use Inter\Sdk\sdkLibrary\pix\enums\ImmediateBillingType;

/**
 * The IncludeLocationRequest class represents a request
 * to include location details for immediate billing.
 *
 * It contains the type of immediate billing that is associated
 * with the location request.
 */
class IncludeLocationRequest
{
    private ?ImmediateBillingType $immediate_billing_type;

    public function __construct(?ImmediateBillingType $immediate_billing_type = null)
    {
        $this->immediate_billing_type = $immediate_billing_type;
    }

    public static function fromJson(array $data): self
    {
        return new self(
            isset($data['tipoCob']) ? ImmediateBillingType::fromString($data['tipoCob']) : null
        );
    }

    public function toArray(): array
    {
        return [
            "tipoCob" => $this->immediate_billing_type?->value
        ];
    }
}