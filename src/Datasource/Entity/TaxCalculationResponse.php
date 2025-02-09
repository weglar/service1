<?php
declare(strict_types=1);

namespace App\Datasource\Entity;

class TaxCalculationResponse
{
    private float $amount;
    private string $country;
    private array $taxes;

    public function __construct(float $amount, string $country, array $taxes)
    {
        $this->amount = $amount;
        $this->country = $country;
        $this->taxes = $taxes;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getTaxes(): array
    {
        return $this->taxes;
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'country' => $this->country,
            'taxes' => $this->taxes,
        ];
    }
}