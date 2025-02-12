<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

/**
 * The RetrievedBilling class represents the response containing different
 * formats of a retrieved billing.
 *
 * It includes references to the billing information, the associated
 * billing slip (billet), and the Pix payment details. This class is used to
 * consolidate data from multiple retrieval responses, allowing for easy access
 * to all relevant billing formats in a single structure.
 */
class RetrievedBilling
{
    private ?BillingRetrievingResponse $billing;
    private ?BillingBilletRetrievingResponse $slip;
    private ?BillingPixRetrievingResponse $pix;

    public function __construct(
        ?BillingRetrievingResponse $billing = null,
        ?BillingBilletRetrievingResponse $slip = null,
        ?BillingPixRetrievingResponse $pix = null
    ) {
        $this->billing = $billing;
        $this->slip = $slip;
        $this->pix = $pix;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            isset($json['cobranca']) ? BillingRetrievingResponse::fromJson($json['cobranca']) : null,
            isset($json['boleto']) ? BillingBilletRetrievingResponse::fromJson($json['boleto']) : null,
            isset($json['pix']) ? BillingPixRetrievingResponse::fromJson($json['pix']) : null
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "cobranca" => $this->billing?->toArray(),
            "boleto" => $this->slip?->toArray(),
            "pix" => $this->pix?->toArray()
        ];
    }
}