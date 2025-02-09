<?php
declare(strict_types=1);

namespace App\Domain\Strategy\Tax;

final class GstTaxStrategy extends TaxAbstract
{
    public const TAX_NAME = 'GST';
    protected static ?array $taxRates = null;
}