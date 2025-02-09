<?php
declare(strict_types=1);

namespace App\Domain\Strategy\Tax;

final class HstTaxStrategy extends TaxAbstract
{
    public const TAX_NAME = 'HST';
    protected static ?array $taxRates = null;
}