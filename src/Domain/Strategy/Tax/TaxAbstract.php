<?php
declare(strict_types=1);

namespace App\Domain\Strategy\Tax;

use App\Datasource\Entity\TaxRate;
use App\Datasource\Repository\TaxRateRepository;

abstract class TaxAbstract implements TaxInterface
{
    public const TAX_NAME = '';

    /**
     * Array of TaxRate entities.
     *
     * @var TaxRate[]
     */
    protected static ?array $taxRates = null;

    /**
     * The country for which the tax is being calculated.
     */
    protected ?string $country = null;

    public static function initialize(TaxRateRepository $repository): void
    {
        static::$taxRates = $repository->getRates(static::class::TAX_NAME);
    }

    public function calculate(float $amount): float
    {
        $this->allParametersCheck();

        return $amount * $this->getRate();
    }

    public function getRate(): float
    {
        $this->allParametersCheck();

        foreach (static::$taxRates as $taxRate) {
            if ($taxRate->getCountry() === $this->country) {
                return $taxRate->getRate();
            }
        }

        throw new \RuntimeException("Tax rate for country '$this->country' is not defined.");
    }

    public function supports(string $country): bool
    {
        $this->checkInitialization();

        foreach (static::$taxRates as $taxRate) {
            if ($taxRate->getCountry() === $country) {
                $this->country = $country;
                return true;
            }
        }

        return false;
    }

    protected function allParametersCheck(): void
    {
        $this->checkInitialization();
        $this->checkIfCountryIsSet();
    }

    protected function checkInitialization(): void
    {
        if ( is_null(static::$taxRates) ) {
            throw new \RuntimeException('Strategy not initialized!');
        }
    }

    protected function checkIfCountryIsSet(): void
    {
        if ($this->country === null) {
            throw new \RuntimeException('Country is not set. Call supports() first.');
        }
    }
}