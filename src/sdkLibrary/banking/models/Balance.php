<?php
namespace Inter\Sdk\sdkLibrary\banking\models;
/**
 * The Balance class represents details about the financial balance,
 * including available funds, blocked amounts, and credit limits.
 */
class Balance
{
    /**
     * The available amount of funds.
     *
     * @var float|null
     */
    private ?float $available;
    /**
     * The amount blocked due to checks.
     *
     * @var float|null
     */
    private ?float $checkBlocked;
    /**
     * The amount blocked judicially.
     *
     * @var float|null
     */
    private ?float $judiciallyBlocked;
    /**
     * The amount blocked administratively.
     *
     * @var float|null
     */
    private ?float $administrativelyBlocked;
    /**
     * The credit limit available.
     *
     * @var float|null
     */
    private ?float $limit;
    /**
     * Constructs a new Balance with specified values.
     *
     * @param float|null $available
     * @param float|null $checkBlocked
     * @param float|null $judiciallyBlocked
     * @param float|null $administrativelyBlocked
     * @param float|null $limit
     */
    public function __construct(
        ?float $available = null,
        ?float $checkBlocked = null,
        ?float $judiciallyBlocked = null,
        ?float $administrativelyBlocked = null,
        ?float $limit = null
    ) {
        $this->available = $available;
        $this->checkBlocked = $checkBlocked;
        $this->judiciallyBlocked = $judiciallyBlocked;
        $this->administrativelyBlocked = $administrativelyBlocked;
        $this->limit = $limit;
    }

    /**
     */
    public static function fromJson(mixed $json): self
    {
        return new self(
            ($json['disponivel']) ?? null,
            ($json['bloqueadoCheque']) ?? null,
            ($json['bloqueadoJudicialmente']) ?? null,
            ($json['bloqueadoAdministrativo']) ?? null,
            ($json['limite']) ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'disponivel' => (float) $this->available,
            'bloqueadoCheque' => (float) $this->checkBlocked,
            'bloqueadoJudicialmente' => (float) $this->judiciallyBlocked,
            'bloqueadoAdministrativo' => (float) $this->administrativelyBlocked,
            'limite' => (float) $this->limit,
        ];
    }
}