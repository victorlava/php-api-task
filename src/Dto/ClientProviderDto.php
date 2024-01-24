<?php

namespace App\Dto;

class ClientProviderDto
{
    private string $url;

    private ?float $conversionRate;

    public function __construct(
       private readonly array $settings,
    )
    {
        $this->url = $this->settings['url'];
        $this->conversionRate = $this->settings['conversionRate'] ?? null;
    }

    public function getUrl(): string
    {
       return $this->url;
    }

    public function getConversionRate(): ?float
    {
        return $this->conversionRate;
    }

    public function shouldApplyConversionRate(): bool
    {
        return $this->conversionRate !== null;
    }
}
