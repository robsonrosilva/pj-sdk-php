<?php
namespace Inter\Sdk\sdkLibrary\pix\models;
/**
 * The DetailedImmediatePixBilling class extends the basic charge details by
 * adding additional fields specific to a detailed view of a PIX charge.
 *
 * It includes the location of the transaction, the current status,
 * a copy-paste (copia e cola) representation of the PIX transaction,
 * a revision number, and a list of PIX transactions. This structure
 * provides comprehensive details necessary for tracking and managing
 * specific charge instances within the PIX system.
 */
class DetailedImmediatePixBilling
{
    private ?Debtor $debtor;
    private ?PixValue $value;
    private ?string $key;
    private ?Calendar $calendar;
    private ?string $txid;
    private ?string $status;
    private ?string $pix_copy_and_paste;
    private ?int $revision;
    private array $pix_transactions;
    private ?string $transaction_id;
    private ?Location $loc;
    private ?string $location;
    private ?string $payer_request;
    private array $additional_info;
    public function __construct(
        ?Debtor $debtor = null,
        ?PixValue $value = null,
        ?string $key = null,
        ?Calendar $calendar = null,
        ?string $txid = null,
        ?string $status = null,
        ?string $pix_copy_and_paste = null,
        ?int $revision = null,
        array $pix_transactions = [],
        ?string $transaction_id = null,
        ?Location $loc = null,
        ?string $location = null,
        ?string $payer_request = null,
        array $additional_info = []
    ) {
        $this->debtor = $debtor;
        $this->value = $value;
        $this->key = $key;
        $this->calendar = $calendar;
        $this->txid = $txid;
        $this->status = $status;
        $this->pix_copy_and_paste = $pix_copy_and_paste;
        $this->revision = $revision;
        $this->pix_transactions = $pix_transactions;
        $this->transaction_id = $transaction_id;
        $this->loc = $loc;
        $this->location = $location;
        $this->payer_request = $payer_request;
        $this->additional_info = $additional_info;
    }
    public static function fromJson(mixed $data): self
    {
        return new self(
            isset($data['devedor']) ? Debtor::fromJson($data['devedor']) : null,
            isset($data['valor']) ? PixValue::fromJson($data['valor']) : null,
            $data['chave'] ?? null,
            isset($data['calendario']) ? Calendar::fromJson($data['calendario']) : null,
            $data['txid'] ?? null,
            $data['status'] ?? null,
            $data['pixCopiaECola'] ?? null,
            $data['revisao'] ?? null,
            array_map(fn($pix) => Pix::fromJson($pix), $data['pix'] ?? []),
            $data['transactionId'] ?? null,
            isset($data['loc']) ? Location::fromJson($data['loc']) : null,
            $data['localizacao'] ?? null,
            $data['pedidoPayer'] ?? null,
            array_map(fn($info) => AdditionalInfo::fromJson($info), $data['informacoesAdicionais'] ?? [])
        );
    }
    public function toArray(): array
    {
        return [
            "devedor" => $this->debtor?->toArray(),
            "valor" => $this->value?->toArray(),
            "chave" => $this->key,
            "calendario" => $this->calendar?->toArray(),
            "txid" => $this->txid,
            "status" => $this->status,
            "pixCopiaECola" => $this->pix_copy_and_paste,
            "revisao" => $this->revision,
            "pix" => array_map(fn($pix) => $pix->toArray(), $this->pix_transactions),
            "transactionId" => $this->transaction_id,
            "loc" => $this->loc?->toArray(),
            "localizacao" => $this->location,
            "pedidoPayer" => $this->payer_request,
            "informacoesAdicionais" => array_map(fn($info) => $info->toArray(), $this->additional_info)
        ];
    }
}