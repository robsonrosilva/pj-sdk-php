<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

/**
 * The DueBillingBatchSummary class summarizes the results
 * of a billing batch processing.
 *
 * It includes fields for the creation date of the processing,
 * the status of the processing, and totals for the billing transactions
 * including the total number of charges, denied charges, and created
 * charges in the batch.
 */
class DueBillingBatchSummary
{
    private ?string $processing_creation_date;
    private ?string $processing_status;
    private ?int $total_billing;
    private ?int $total_billing_denied;
    private ?int $total_billing_created;

    public function __construct(
        ?string $processing_creation_date = null,
        ?string $processing_status = null,
        ?int $total_billing = null,
        ?int $total_billing_denied = null,
        ?int $total_billing_created = null
    ) {
        $this->processing_creation_date = $processing_creation_date;
        $this->processing_status = $processing_status;
        $this->total_billing = $total_billing;
        $this->total_billing_denied = $total_billing_denied;
        $this->total_billing_created = $total_billing_created;
    }

    public static function fromJson(mixed $data): self
    {
        return new self(
            $data['dataCriacaoProcessamento'] ?? null,
            $data['statusProcessamento'] ?? null,
            $data['totalCobrancas'] ?? null,
            $data['totalCobrancasNegadas'] ?? null,
            $data['totalCobrancasCriadas'] ?? null
        );
    }

    /**
     */
    public function toArray(): array
    {
        return [
            "dataCriacaoProcessamento" => $this->processing_creation_date,
            "statusProcessamento" => $this->processing_status,
            "totalCobrancas" => $this->total_billing,
            "totalCobrancasNegadas" => $this->total_billing_denied,
            "totalCobrancasCriadas" => $this->total_billing_created
        ];
    }
}