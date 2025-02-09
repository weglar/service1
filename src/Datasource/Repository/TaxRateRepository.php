<?php
declare(strict_types=1);

namespace App\Datasource\Repository;

use App\Datasource\Client\TaxDataClientInterface;
use App\Datasource\Entity\TaxRate;

class TaxRateRepository
{
    private array $taxRates;

    public function __construct(TaxDataClientInterface $dataClient)
    {
        $data = $dataClient->load();
        $this->taxRates = $data['tax_rates'] ?? [];
    }

    public function getRates(string $category): array
    {
        if (!isset($this->taxRates[$category])) {
            throw new \RuntimeException("Tax category '$category' not found.");
        }

        return array_map(fn($entry) => new TaxRate($entry['country'], $entry['rate']), $this->taxRates[$category]);
    }
}
