<?php

namespace Inter\Sdk\sdkLibrary\pix\enums;

use ValueError;

/**
 * The AgentModality enum represents the different types of agent modalities
 * in the PIX system.
 */
enum AgentModality: string
{
    /**
     * Agente Estabelecimento Comercial (Commercial Establishment Agent)
     */
    case AGTEC = 'AGTEC';

    /**
     * Agente Outra Espécie de Pessoa Jurídica ou Correspondente no País
     * (Other Type of Legal Entity Agent or Correspondent in the Country)
     */
    case AGTOT = 'AGTOT';

    /**
     * Agente Facilitador de Serviço de Saque (Withdrawal Service Facilitator Agent)
     */
    case AGPSS = 'AGPSS';

    /**
     * Create an AgentModality instance from a string value.
     *
     * @param string $value The string representation of the AgentModality.
     * @return AgentModality The corresponding AgentModality enum value.
     * @throws ValueError If the input string doesn't match any AgentModality value.
     */
    public static function fromString(string $value): self
    {
        $upperValue = strtoupper($value);

        try {
            return self::from($upperValue);
        } catch (\ValueError $e) {
            throw new \ValueError("'{$value}' is not a valid AgentModality value");
        }
    }
}