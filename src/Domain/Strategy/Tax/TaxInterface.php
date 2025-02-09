<?php
declare(strict_types=1);

namespace App\Domain\Strategy\Tax;

use App\Datasource\Repository\TaxRateRepository;

interface TaxInterface
{
    public function calculate(float $amount): float;
    public function supports(string $country): bool;
    public function getRate (): float;
    public static function initialize(TaxRateRepository $repository): void;
}