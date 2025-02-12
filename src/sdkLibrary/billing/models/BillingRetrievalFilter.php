<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

use Inter\Sdk\sdkLibrary\billing\enums\BillingDateType;
use Inter\Sdk\sdkLibrary\billing\enums\BillingSituation;
use Inter\Sdk\sdkLibrary\billing\enums\BillingType;

/**
 * The BillingRetrievalFilter class extends the base filter
 * class for retrieving billing information.
 *
 * It includes pagination details, specifically the page
 * number and the number of items per page. This structure is used
 * to specify filtering criteria when retrieving billing data from a
 * paginated source.
 */
class BillingRetrievalFilter extends BaseBillingRetrievalFilter
{
    private int $page;
    private int $items_per_page;

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getItemsPerPage(): int
    {
        return $this->items_per_page;
    }

    public function setItemsPerPage(int $items_per_page): void
    {
        $this->items_per_page = $items_per_page;
    }

    public function __construct(
        ?BillingDateType $filter_date_by = null,
        ?BillingSituation $situation = null,
        ?string $payer = null,
        ?string $payer_cpf_cnpj = null,
        ?string $your_number = null,
        ?BillingType $billing_type = null,
        int $page = 0,
        int $items_per_page = 0
    ) {
        parent::__construct($filter_date_by, $situation, $payer, $payer_cpf_cnpj, $your_number, $billing_type);
        $this->page = $page;
        $this->items_per_page = $items_per_page;
    }

    public static function fromJson(mixed $json): self
    {
        $base_filter = BaseBillingRetrievalFilter::fromJson($json);
        return new self(
            $base_filter->getFilterDateBy(),
            $base_filter->getSituation(),
            $base_filter->getPayer(),
            $base_filter->getPayerCpfCnpj(),
            $base_filter->getYourNumber(),
            $base_filter->getBillingType(),
            $json['pagina'] ?? 0,
            $json['itensPorPagina'] ?? 0
        );
    }
}