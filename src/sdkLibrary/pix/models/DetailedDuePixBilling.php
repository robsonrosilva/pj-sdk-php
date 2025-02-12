<?php

namespace Inter\Sdk\sdkLibrary\pix\models;

use Inter\Sdk\sdkLibrary\pix\enums\BillingStatus;

/**
 * The DetailedDuePixBilling class extends the DueBilling
 * class and provides detailed information about a billing transaction
 * that is due.
 *
 * It includes fields for the PIX (copy and paste)
 * information, receiver details, billing status, revision number,
 * and a list of PIX transactions associated with the billing.
 */
class DetailedDuePixBilling extends DueBilling
{
    private ?string $pix_copy_and_paste;
    private ?Receiver $receiver;
    private ?BillingStatus $status;
    private ?int $revision;
    private array $pix_transactions;

    public function __construct(
        ?string $pix_copy_and_paste = null,
        ?Receiver $receiver = null,
        ?BillingStatus $status = null,
        ?int $revision = null,
        array $pix_transactions = []
    ) {
        parent::__construct();
        $this->pix_copy_and_paste = $pix_copy_and_paste;
        $this->receiver = $receiver;
        $this->status = $status;
        $this->revision = $revision;
        $this->pix_transactions = $pix_transactions;
    }

    public static function fromJson(mixed $data): self
    {
        $dueBilling = DueBilling::fromJson($data);
        return new self(
            $data['pixCopiaECola'] ?? null,
            isset($data['recebedor']) ? Receiver::fromJson($data['recebedor']) : null,
            isset($data['status']) ? BillingStatus::fromString($data['status']) : null,
            $data['revisao'] ?? null,
            array_map(/**
             * @throws \DateMalformedStringException
             */ fn($pix) => Pix::fromJson($pix), $data['pix'] ?? [])
        );
    }

    public function toArray(): array
    {
        $dueBillingArray = parent::toArray();
        $dueBillingArray = array_merge($dueBillingArray, [
            "pixCopiaECola" => $this->pix_copy_and_paste,
            "recebedor" => $this->receiver?->toArray(),
            "status" => $this->status?->value,
            "revisao" => $this->revision,
            "pix" => array_map(fn($pix) => $pix->toArray(), $this->pix_transactions)
        ]);
        return $dueBillingArray;
    }
}