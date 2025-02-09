<?php
declare(strict_types=1);

namespace App\Domain\Strategy\Tax;

final class VatTaxStrategy extends TaxAbstract
{
    public const TAX_NAME = 'VAT';
    protected static ?array $taxRates = null;
}