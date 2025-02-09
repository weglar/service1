<?php
declare(strict_types=1);

namespace App\Datasource\Client;

use Symfony\Component\Yaml\Yaml;

class YamlClient implements TaxDataClientInterface
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function load(): array
    {
        return Yaml::parseFile($this->filePath) ?? [];
    }
}
