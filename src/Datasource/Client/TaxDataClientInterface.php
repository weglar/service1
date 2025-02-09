<?php
declare(strict_types=1);

namespace App\Datasource\Client;

interface TaxDataClientInterface
{
    /**
     * Load tax rates from the data source.
     *
     * @return array The parsed tax rates data.
     */
    public function load(): array;
}
