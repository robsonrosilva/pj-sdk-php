<?php

namespace Inter\Sdk\sdkLibrary\billing\models;

/**
 * The BillingIssueResponse class represents the response received after
 * issuing a billing statement, containing the request code assigned automatically
 * by the bank upon the issuance of the title.
 *
 * This response is critical for confirming successful billing operations,
 * allowing users to track or reference their requests based on the generated request code.
 */
class BillingIssueResponse
{
    private ?string $request_code;

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