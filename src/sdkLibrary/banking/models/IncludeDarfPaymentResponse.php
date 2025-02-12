<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The IncludeDarfPaymentResponse class represents the response for including a DARF payment,
 * including approval details, authentication, payment date, return type, and request code.
 */
class IncludeDarfPaymentResponse
{
    /**
     * The quantity of approvers required for the DARF payment.
     *
     * @var string|null
     */
    private ?string $approverQuantity;

    public function getApproverQuantity(): ?string
    {
        return $this->approverQuantity;
    }

    public function setApproverQuantity(?string $approverQuantity): void
    {
        $this->approverQuantity = $approverQuantity; // Define a quantidade de aprovadores
    }

    /**
     * An authentication token or information for the payment request.
     *
     * @var string|null
     */
    private ?string $authentication;

    public function getAuthentication(): ?string
    {
        return $this->authentication;
    }

    public function setAuthentication(?string $authentication): void
    {
        $this->authentication = $authentication; // Define o token de autenticação
    }

    /**
     * The date when the payment is scheduled or was processed.
     *
     * @var string|null
     */
    private ?string $paymentDate;

    public function getPaymentDate(): ?string
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(?string $paymentDate): void
    {
        $this->paymentDate = $paymentDate; // Define a data do pagamento
    }

    /**
     * The type of return expected from the payment process.
     *
     * @var string|null
     */
    private ?string $returnType;

    public function getReturnType(): ?string
    {
        return $this->returnType;
    }

    public function setReturnType(?string $returnType): void
    {
        $this->returnType = $returnType; // Define o tipo de retorno esperado
    }

    /**
     * The unique code associated with the payment request.
     *
     * @var string|null
     */
    private ?string $requestCode;

    public function getRequestCode(): ?string
    {
        return $this->requestCode;
    }

    public function setRequestCode(?string $requestCode): void
    {
        $this->requestCode = $requestCode; // Define o código de solicitação
    }

    /**
     * Constructs a new IncludeDarfPaymentResponse with specified values.
     *
     * @param string|null $approverQuantity
     * @param string|null $authentication
     * @param string|null $paymentDate
     * @param string|null $returnType
     * @param string|null $requestCode
     */
    public function __construct(
        ?string $approverQuantity = null,
        ?string $authentication = null,
        ?string $paymentDate = null,
        ?string $returnType = null,
        ?string $requestCode = null
    ) {
        $this->approverQuantity = $approverQuantity;
        $this->authentication = $authentication;
        $this->paymentDate = $paymentDate;
        $this->returnType = $returnType;
        $this->requestCode = $requestCode;
    }

    /**
     * Creates an IncludeDarfPaymentResponse instance from a JSON string.
     *
     * @param string $json The JSON string containing the response data.
     * @return IncludeDarfPaymentResponse An instance of IncludeDarfPaymentResponse.
     */
    public static function fromJson(mixed $json): self
    {
        return new self(
            $json['quantidadeAprovadores'] ?? null,
                $json['autenticacao'] ?? null,
                $json['dataPagamento'] ?? null,
                $json['tipoRetorno'] ?? null,
                $json['codigoSolicitacao'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'quantidadeAprovadores' => $this->getApproverQuantity(),
            'autenticacao' => $this->getAuthentication(),
            'dataPagamento' => $this->getPaymentDate(),
            'tipoRetorno' => $this->getReturnType(),
            'codigoSolicitacao' => $this->getRequestCode(),
        ];
    }
}