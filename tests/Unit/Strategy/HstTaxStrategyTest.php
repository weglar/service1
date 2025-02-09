<?php
declare(strict_types=1);

namespace App\Tests\Unit\Strategy;

use App\Datasource\Client\YamlClient;
use App\Datasource\Repository\TaxRateRepository;
use App\Domain\Strategy\Tax\HstTaxStrategy;
use PHPUnit\Framework\TestCase;

class HstTaxStrategyTest extends TestCase
{
/*
    public function testCalculateWithoutInitialize()
    {
        // Create an instance of GstTaxStrategy WITHOUT calling initialize()
        $tax = new HstTaxStrategy();
    
        // Expect exception when trying to use the strategy without initialization
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Strategy not initialized!');
    
        // This will trigger the exception because initialize() was not called
        $tax->supports('CA');
    }
//*/

    public function testCalculate()
    {
        $amount = 100.0;

        $tax = new HstTaxStrategy();
        
        $client = new YamlClient("config/tax_rates.yaml");
        $repository = new TaxRateRepository($client);
        $tax::initialize($repository);

        $tax->supports('CA');
        $this->assertEquals($tax->getRate()*$amount, $tax->calculate($amount));
    }

    public function testSupports()
    {
        $tax = new HstTaxStrategy();
        
        $client = new YamlClient("config/tax_rates.yaml");
        $repository = new TaxRateRepository($client);
        $tax::initialize($repository);

        $this->assertTrue($tax->supports('CA'));
        $this->assertFalse($tax->supports('US'));
    }

    public function testCalculateWithoutSupports()
    {
        $tax = new HstTaxStrategy();
        
        $client = new YamlClient("config/tax_rates.yaml");
        $repository = new TaxRateRepository($client);
        $tax::initialize($repository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Country is not set. Call supports() first.');
        $tax->calculate(100.0);
    }
}