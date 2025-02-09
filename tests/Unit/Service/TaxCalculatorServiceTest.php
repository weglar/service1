<?php
declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Datasource\Client\YamlClient;
use App\Datasource\Repository\TaxRateRepository;
use App\Datasource\Entity\TaxCalculationRequest;
use App\Datasource\Entity\TaxCalculationResponse;
use App\Exception\ValidationException;
use App\Domain\Service\TaxCalculatorService;
use App\Domain\Strategy\Tax\GstTaxStrategy;
use App\Domain\Strategy\Tax\HstTaxStrategy;
use App\Domain\Strategy\Tax\VatTaxStrategy;
use App\Domain\UseCase\TaxStrategyInitializerUseCase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaxCalculatorServiceTest extends TestCase
{
    private TaxCalculatorService $taxCalculatorService;
    private ValidatorInterface $validator;
    private TaxStrategyInitializerUseCase $taxStrategyInitializerUseCase;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->taxStrategyInitializerUseCase = $this->createMock(TaxStrategyInitializerUseCase::class);
        $this->taxCalculatorService = new TaxCalculatorService([], $this->validator,  $this->taxStrategyInitializerUseCase);
    }

    public function testCalculateTaxWithValidRequest()
    {
        $this->validator->method('validate')->willReturn($this->createMock(ConstraintViolationListInterface::class));

        $request = new TaxCalculationRequest(100.0, 'FR');

        $response = $this->taxCalculatorService->calculateTax($request);

        $this->assertInstanceOf(TaxCalculationResponse::class, $response);
        $this->assertEquals(100.0, $response->getAmount());
        $this->assertEquals('FR', $response->getCountry());
        $this->assertIsArray($response->getTaxes());
    }

    public function testCalculateTaxWithInvalidRequest()
    {
        // Create a TaxCalculationRequest with invalid data
        $request = new TaxCalculationRequest('abc', '');

        $violations = $this->createMock(ConstraintViolationListInterface::class);
        $violations->method('count')->willReturn(2);
        $this->validator->method('validate')->willReturn($violations);

        $this->expectException(ValidationException::class);

        $this->taxCalculatorService->calculateTax($request);
    }

    public function testCalculateTaxWithMultipleHandlers()
    {
        $this->validator->method('validate')->willReturn($this->createMock(ConstraintViolationListInterface::class));

        $taxHandlers = [new VatTaxStrategy(), new GstTaxStrategy(), new HstTaxStrategy()];

        $client = new YamlClient("config/tax_rates.yaml");
        $repository = new TaxRateRepository($client);
        $this->taxStrategyInitializerUseCase = new TaxStrategyInitializerUseCase($repository);

        $this->taxCalculatorService = new TaxCalculatorService(
            $taxHandlers,
            $this->validator,
            $this->taxStrategyInitializerUseCase
        );

        $request = new TaxCalculationRequest(100.0, 'CA'); 

        $response = $this->taxCalculatorService->calculateTax($request);

        $this->assertArrayHasKey(HstTaxStrategy::class, $response->getTaxes());
    }
}