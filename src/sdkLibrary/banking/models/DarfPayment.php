<?php

namespace Inter\Sdk\sdkLibrary\banking\models;

use JsonException;

/**
 * The DarfPayment class represents a payment for the DARF (Documento de Arrecadação de Receitas Federais),
 * including various details related to the payment.
 */
class DarfPayment
{
    /**
     * The CNPJ or CPF associated with the payment.
     *
     * @var string|null
     */
    private ?string $cnpjOrCpf;
    public function getCnpjOrCpf(): ?string
    {
        return $this->cnpjOrCpf;
    }
    public function setCnpjOrCpf(?string $cnpjOrCpf): void
    {
        $this->cnpjOrCpf = $cnpjOrCpf; // Define o CNPJ ou CPF
    }
    /**
     * The revenue code related to the payment.
     *
     * @var string|null
     */
    private ?string $revenueCode;
    public function getRevenueCode(): ?string
    {
        return $this->revenueCode;
    }
    public function setRevenueCode(?string $revenueCode): void
    {
        $this->revenueCode = $revenueCode; // Define o código de receita
    }
    /**
     * The due date for the payment.
     *
     * @var string|null
     */
    private ?string $dueDate;
    public function getDueDate(): ?string
    {
        return $this->dueDate;
    }
    public function setDueDate(?string $dueDate): void
    {
        $this->dueDate = $dueDate; // Define a data de vencimento
    }
    /**
     * A description of the payment.
     *
     * @var string|null
     */
    private ?string $description;
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): void
    {
        $this->description = $description; // Define a descrição do pagamento
    }
    /**
     * The name of the enterprise making the payment.
     *
     * @var string|null
     */
    private ?string $enterpriseName;
    public function getEnterpriseName(): ?string
    {
        return $this->enterpriseName;
    }
    public function setEnterpriseName(?string $enterpriseName): void
    {
        $this->enterpriseName = $enterpriseName; // Define o nome da empresa
    }
    /**
     * The contact phone number of the enterprise.
     *
     * @var string|null
     */
    private ?string $enterprisePhone;
    public function getEnterprisePhone(): ?string
    {
        return $this->enterprisePhone;
    }
    public function setEnterprisePhone(?string $enterprisePhone): void
    {
        $this->enterprisePhone = $enterprisePhone; // Define o telefone da empresa
    }
    /**
     * The assessment period for the revenue being paid.
     *
     * @var string|null
     */
    private ?string $assessmentPeriod;
    public function getAssessmentPeriod(): ?string
    {
        return $this->assessmentPeriod;
    }
    public function setAssessmentPeriod(?string $assessmentPeriod): void
    {
        $this->assessmentPeriod = $assessmentPeriod; // Define o período de apuração
    }
    /**
     * The date when the payment was made.
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
     * The date when the payment was included in the system.
     *
     * @var string|null
     */
    private ?string $inclusionDate;
    public function getInclusionDate(): ?string
    {
        return $this->inclusionDate;
    }
    public function setInclusionDate(?string $inclusionDate): void
    {
        $this->inclusionDate = $inclusionDate; // Define a data de inclusão do pagamento
    }
    /**
     * The value of the payment.
     *
     * @var string|null
     */
    private ?string $value;
    public function getValue(): ?string
    {
        return $this->value;
    }
    public function setValue(?string $value): void
    {
        $this->value = $value; // Define o valor do pagamento
    }
    /**
     * The total value to be paid including fines and interest.
     *
     * @var string|null
     */
    private ?string $totalValue;
    public function getTotalValue(): ?string
    {
        return $this->totalValue;
    }
    public function setTotalValue(?string $totalValue): void
    {
        $this->totalValue = $totalValue; // Define o valor total a ser pago
    }
    /**
     * The amount of any fines applied to the payment.
     *
     * @var string|null
     */
    private ?string $fineAmount;
    public function getFineAmount(): ?string
    {
        return $this->fineAmount;
    }
    public function setFineAmount(?string $fineAmount): void
    {
        $this->fineAmount = $fineAmount; // Define o valor da multa
    }
    /**
     * The amount of any interest applied to the payment.
     *
     * @var string|null
     */
    private ?string $interestAmount;
    public function getInterestAmount(): ?string
    {
        return $this->interestAmount;
    }
    public function setInterestAmount(?string $interestAmount): void
    {
        $this->interestAmount = $interestAmount; // Define o valor dos juros
    }
    /**
     * Any reference number related to the payment.
     *
     * @var string|null
     */
    private ?string $reference;
    public function getReference(): ?string
    {
        return $this->reference;
    }
    public function setReference(?string $reference): void
    {
        $this->reference = $reference; // Define o número de referência
    }
    /**
     * The type of DARF payment.
     *
     * @var string|null
     */
    private ?string $darfType;
    public function getDarfType(): ?string
    {
        return $this->darfType;
    }
    public function setDarfType(?string $darfType): void
    {
        $this->darfType = $darfType; // Define o tipo de DARF
    }
    /**
     * The specific type of related payment.
     *
     * @var string|null
     */
    private ?string $type;
    public function getType(): ?string
    {
        return $this->type;
    }
    public function setType(?string $type): void
    {
        $this->type = $type; // Define o tipo de pagamento
    }
    /**
     * The principal amount being paid.
     *
     * @var string|null
     */
    private ?string $principalValue;
    public function getPrincipalValue(): ?string
    {
        return $this->principalValue;
    }
    public function setPrincipalValue(?string $principalValue): void
    {
        $this->principalValue = $principalValue; // Define o valor principal a ser pago
    }

    /**
     * Constructs a new DarfPayment with specified values.
     *
     * @param string|null $cnpjOrCpf
     * @param string|null $revenueCode
     * @param string|null $dueDate
     * @param string|null $description
     * @param string|null $enterpriseName
     * @param string|null $enterprisePhone
     * @param string|null $assessmentPeriod
     * @param string|null $paymentDate
     * @param string|null $inclusionDate
     * @param string|null $value
     * @param string|null $totalValue
     * @param string|null $fineAmount
     * @param string|null $interestAmount
     * @param string|null $reference
     * @param string|null $darfType
     * @param string|null $type
     * @param string|null $principalValue
     */
    public function __construct(
        ?string $cnpjOrCpf = null,
        ?string $revenueCode = null,
        ?string $dueDate = null,
        ?string $description = null,
        ?string $enterpriseName = null,
        ?string $enterprisePhone = null,
        ?string $assessmentPeriod = null,
        ?string $paymentDate = null,
        ?string $inclusionDate = null,
        ?string $value = null,
        ?string $totalValue = null,
        ?string $fineAmount = null,
        ?string $interestAmount = null,
        ?string $reference = null,
        ?string $darfType = null,
        ?string $type = null,
        ?string $principalValue = null
    ) {
        $this->cnpjOrCpf = $cnpjOrCpf;
        $this->revenueCode = $revenueCode;
        $this->dueDate = $dueDate;
        $this->description = $description;
        $this->enterpriseName = $enterpriseName;
        $this->enterprisePhone = $enterprisePhone;
        $this->assessmentPeriod = $assessmentPeriod;
        $this->paymentDate = $paymentDate;
        $this->inclusionDate = $inclusionDate;
        $this->value = $value;
        $this->totalValue = $totalValue;
        $this->fineAmount = $fineAmount;
        $this->interestAmount = $interestAmount;
        $this->reference = $reference;
        $this->darfType = $darfType;
        $this->type = $type;
        $this->principalValue = $principalValue;
    }

    /**
     * Convert the DarfPayment instance to a dictionary.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            "cnpjCpf" => $this->cnpjOrCpf,
            "codigoReceita" => $this->revenueCode,
            "dataVencimento" => $this->dueDate,
            "descricao" => $this->description,
            "nomeEmpresa" => $this->enterpriseName,
            "telefoneEmpresa" => $this->enterprisePhone,
            "periodoApuracao" => $this->assessmentPeriod,
            "dataPagamento" => $this->paymentDate,
            "dataInclusao" => $this->inclusionDate,
            "valor" => $this->value,
            "valorTotal" => $this->totalValue,
            "valorMulta" => $this->fineAmount,
            "valorJuros" => $this->interestAmount,
            "referencia" => $this->reference,
            "tipoDarf" => $this->darfType,
            "tipo" => $this->type,
            "valorPrincipal" => $this->principalValue
        ];
    }

    /**
     * Convert the DarfPayment instance to JSON string.
     *
     * @return string
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }
    /**
     * Create a DarfPayment instance from an associative array.
     *
     * @param array $data
     * @return DarfPayment
     */
    public static function fromJson(array $data): self
    {
        return new self(
            $data['cnpjCpf'] ?? null,
            $data['codigoReceita'] ?? null,
            $data['dataVencimento'] ?? null,
            $data['descricao'] ?? null,
            $data['nomeEmpresa'] ?? null,
            $data['telefoneEmpresa'] ?? null,
            $data['periodoApuracao'] ?? null,
            $data['dataPagamento'] ?? null,
            $data['dataInclusao'] ?? null,
            $data['valor'] ?? null,
            $data['valorTotal'] ?? null,
            $data['valorMulta'] ?? null,
            $data['valorJuros'] ?? null,
            $data['referencia'] ?? null,
            $data['tipoDarf'] ?? null,
            $data['tipo'] ?? null,
            $data['valorPrincipal'] ?? null
        );
    }

    public static function builder(): DarfPaymentBuilder
    {
        return new DarfPaymentBuilder();
    }
}

class DarfPaymentBuilder
{
    private ?string $cnpjOrCpf = null;
    private ?string $revenueCode = null;
    private ?string $dueDate = null;
    private ?string $description = null;
    private ?string $enterpriseName = null;
    private ?string $enterprisePhone = null;
    private ?string $assessmentPeriod = null;
    private ?string $paymentDate = null;
    private ?string $inclusionDate = null;
    private ?string $value = null;
    private ?string $totalValue = null;
    private ?string $fineAmount = null;
    private ?string $interestAmount = null;
    private ?string $reference = null;
    private ?string $darfType = null;
    private ?string $type = null;
    private ?string $principalValue = null;

    public function setCnpjOrCpf(?string $cnpjOrCpf): self
    {
        $this->cnpjOrCpf = $cnpjOrCpf;
        return $this;
    }

    public function setRevenueCode(?string $revenueCode): self
    {
        $this->revenueCode = $revenueCode;
        return $this;
    }

    public function setDueDate(?string $dueDate): self
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setEnterpriseName(?string $enterpriseName): self
    {
        $this->enterpriseName = $enterpriseName;
        return $this;
    }

    public function setEnterprisePhone(?string $enterprisePhone): self
    {
        $this->enterprisePhone = $enterprisePhone;
        return $this;
    }

    public function setAssessmentPeriod(?string $assessmentPeriod): self
    {
        $this->assessmentPeriod = $assessmentPeriod;
        return $this;
    }

    public function setPaymentDate(?string $paymentDate): self
    {
        $this->paymentDate = $paymentDate;
        return $this;
    }

    public function setInclusionDate(?string $inclusionDate): self
    {
        $this->inclusionDate = $inclusionDate;
        return $this;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function setTotalValue(?string $totalValue): self
    {
        $this->totalValue = $totalValue;
        return $this;
    }

    public function setFineAmount(?string $fineAmount): self
    {
        $this->fineAmount = $fineAmount;
        return $this;
    }

    public function setInterestAmount(?string $interestAmount): self
    {
        $this->interestAmount = $interestAmount;
        return $this;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    public function setDarfType(?string $darfType): self
    {
        $this->darfType = $darfType;
        return $this;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function setPrincipalValue(?string $principalValue): self
    {
        $this->principalValue = $principalValue;
        return $this;
    }

    public function build(): DarfPayment
    {
        $darfPayment = new DarfPayment();

        $darfPayment->setCnpjOrCpf($this->cnpjOrCpf);
        $darfPayment->setRevenueCode($this->revenueCode);
        $darfPayment->setDueDate($this->dueDate);
        $darfPayment->setDescription($this->description);
        $darfPayment->setEnterpriseName($this->enterpriseName);
        $darfPayment->setEnterprisePhone($this->enterprisePhone);
        $darfPayment->setAssessmentPeriod($this->assessmentPeriod);
        $darfPayment->setPaymentDate($this->paymentDate);
        $darfPayment->setInclusionDate($this->inclusionDate);
        $darfPayment->setValue($this->value);
        $darfPayment->setTotalValue($this->totalValue);
        $darfPayment->setFineAmount($this->fineAmount);
        $darfPayment->setInterestAmount($this->interestAmount);
        $darfPayment->setReference($this->reference);
        $darfPayment->setDarfType($this->darfType);
        $darfPayment->setType($this->type);
        $darfPayment->setPrincipalValue($this->principalValue);

        return $darfPayment;
    }
}