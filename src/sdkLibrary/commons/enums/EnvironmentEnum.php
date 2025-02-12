<?php

namespace Inter\Sdk\sdkLibrary\commons\enums;

class EnvironmentEnum
{
    private string $label;
    private string $urlBase;

    private function __construct(string $label, string $urlBase)
    {
        $this->label = $label;
        $this->urlBase = $urlBase;
    }

    public static function PRODUCTION(): self
    {
        return new self('PRODUCTION', 'https://cdpj.partners.bancointer.com.br');
    }

    public static function UAT(): self
    {
        return new self('UAT', 'https://cdpj.partners.uatbi.com.br');
    }

    public static function SANDBOX(): self
    {
        return new self('SANDBOX', 'https://cdpj-sandbox.partners.uatinter.co');
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUrlBase(): string
    {
        return $this->urlBase;
    }

    public static function fromLabel(string $label): self
    {
        $environments = [self::PRODUCTION(), self::UAT(), self::SANDBOX()];
        foreach ($environments as $environment) {
            if ($environment->getLabel() === $label) {
                return $environment;
            }
        }
        throw new \InvalidArgumentException("Invalid environment label: $label");
    }

}