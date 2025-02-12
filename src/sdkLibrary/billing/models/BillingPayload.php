<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

use Inter\Sdk\sdkLibrary\billing\enums\BillingSituation;
use Inter\Sdk\sdkLibrary\billing\enums\ReceivingOrigin;

/**
 * The BillingPayload class represents the data structure used for
 * handling billing information.
 *
 * It includes attributes such as unique request code, user-defined
 * numbers, billing status, receipt details, and payment identifiers. This
 * structure is essential for managing the flow of billing data within the
 * application.
 */
class BillingPayload
{
    private ?string $request_code;
    private ?string $your_number;
    private ?BillingSituation $situation;
    private ?string $status_date_time;
    private ?string $total_amount_received;
    private ?ReceivingOrigin $receiving_origin;
    private ?string $our_number;
    private ?string $barcode;
    private ?string $payment_line;
    private ?string $txid;
    private ?string $pix_copy_and_paste;

    public function __construct(
        ?string $request_code = null,
        ?string $your_number = null,
        ?BillingSituation $situation = null,
        ?string $status_date_time = null,
        ?string $total_amount_received = null,
        ?ReceivingOrigin $receiving_origin = null,
        ?string $our_number = null,
        ?string $barcode = null,
        ?string $payment_line = null,
        ?string $txid = null,
        ?string $pix_copy_and_paste = null
    ) {
        $this->request_code = $request_code;
        $this->your_number = $your_number;
        $this->situation = $situation;
        $this->status_date_time = $status_date_time;
        $this->total_amount_received = $total_amount_received;
        $this->receiving_origin = $receiving_origin;
        $this->our_number = $our_number;
        $this->barcode = $barcode;
        $this->payment_line = $payment_line;
        $this->txid = $txid;
        $this->pix_copy_and_paste = $pix_copy_and_paste;
    }

    public static function fromJson(array $json): self
    {
        return new self(
            $json['codigoSolicitacao'] ?? null,
            $json['seuNumero'] ?? null,
            isset($json['situacao']) ? BillingSituation::fromString($json['situacao']) : null,
            $json['dataHoraSituacao'] ?? null,
            $json['valorTotalRecebido'] ?? null,
            isset($json['origemRecebimento']) ? ReceivingOrigin::fromString($json['origemRecebimento']) : null,
            $json['nossoNumero'] ?? null,
            $json['codigoBarras'] ?? null,
            $json['linhaDigitavel'] ?? null,
            $json['txid'] ?? null,
            $json['pixCopiaECola'] ?? null
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return [
            "codigoSolicitacao" => $this->request_code,
            "seuNumero" => $this->your_number,
            "situacao" => $this->situation?->value,
            "dataHoraSituacao" => $this->status_date_time,
            "valorTotalRecebido" => $this->total_amount_received,
            "origemRecebimento" => $this->receiving_origin?->value,
            "nossoNumero" => $this->our_number,
            "codigoBarras" => $this->barcode,
            "linhaDigitavel" => $this->payment_line,
            "txid" => $this->txid,
            "pixCopiaECola" => $this->pix_copy_and_paste
        ];
    }
}