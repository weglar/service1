<?php
declare(strict_types=1);

namespace App\Tests\Unit\Strategy;

use App\Datasource\Client\YamlClient;
use App\Datasource\Repository\TaxRateRepository;
use App\Domain\Strategy\Tax\CustomsTaxStrategy;
use App\Domain\Strategy\Tax\GstTaxStrategy;
use PHPUnit\Framework\TestCase;

class CustomsTaxStrategyTest extends TestCase
{
/*
    public function testCalculateWithoutInitialize()
    {
        // Create an instance of GstTaxStrategy WITHOUT calling initialize()
        $tax = new GstTaxStrategy();
    
        // Expect exception when trying to use the strategy without initialization
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Strategy not initialized!');
    
        // This will trigger the exception because initialize() was not called
        $tax->supports('AU'); // Set the country
    }
//*/

    public function testCalculate()
    {
        $amount = 100.0;

        $tax = new CustomsTaxStrategy();

        $client = new YamlClient("config/tax_rates.yaml");
        $repository = new TaxRateRepository($client);
        $tax::initialize($repository);

        $tax->supports('MEX');
        $this->assertEquals($tax->getRate()*$amount, $tax->calculate($amount));
    }

    public function testSupports()
    {
        $tax = new CustomsTaxStrategy();

        $client = new YamlClient("config/tax_rates.yaml");
        $repository = new TaxRateRepository($client);
        $tax::initialize($repository);

        $this->assertTrue($tax->supports('PL'));
        $this->assertFalse($tax->supports('US'));
    }

    public function testCalculateWithoutSupports()
    {
        $tax = new CustomsTaxStrategy();
        
        $client = new YamlClient("config/tax_rates.yaml");
        $repository = new TaxRateRepository($client);
        $tax::initialize($repository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Country is not set. Call supports() first.');
        $tax->calculate(100.0);
    }      
}