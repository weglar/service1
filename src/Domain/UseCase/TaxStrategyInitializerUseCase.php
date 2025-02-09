<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Datasource\Repository\TaxRateRepository;

class TaxStrategyInitializerUseCase
{
    public function __construct(private readonly TaxRateRepository $taxRateRepository ) {}

    public function execute(iterable $taxHandlers): void
    {
        foreach ($taxHandlers as $handler) {
            if (method_exists($handler, 'initialize')) {
                $handler::initialize($this->taxRateRepository);
            }
        }
    }
}
