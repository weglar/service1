<?php
declare(strict_types=1);

namespace App\Domain\Strategy\Tax;

final class CustomsTaxStrategy extends TaxAbstract
{
    public const TAX_NAME = 'CUSTOM';
    protected static ?array $taxRates = null;
}