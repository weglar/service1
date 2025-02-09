<?php
declare(strict_types=1);

namespace App\Tests\Unit\Strategy;

use App\Datasource\Client\YamlClient;
use App\Datasource\Repository\TaxRateRepository;
use App\Domain\Strategy\Tax\VatTaxStrategy;
use PHPUnit\Framework\TestCase;

class VatTaxStrategyTest extends TestCase
{
/*
    public function testCalculateWithoutInitialize()
    {
        // Create an instance of GstTaxStrategy WITHOUT calling initialize()
        $tax = new VatTaxStrategy();
    
        // Expect exception when trying to use the strategy without initialization
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Strategy not initialized!');
    
        // This will trigger the exception because initialize() was not called
        $tax->supports('PL');
    }
//*/

    public function testCalculate()
    {
        $amount = 100.0;

        $tax = new VatTaxStrategy();
        
        $client = new YamlClient("config/tax_rates.yaml");
        $repository = new TaxRateRepository($client);
        $tax::initialize($repository);

        $tax->supports('PL');
        $this->assertEquals($tax->getRate()*$amount, $tax->calculate($amount));

        $tax = new VatTaxStrategy();
        $tax->supports('DE');
        $this->assertEquals($tax->getRate()*$amount, $tax->calculate($amount));
    }

    public function testSupports()
    {
        $tax = new VatTaxStrategy();
        
        $client = new YamlClient("config/tax_rates.yaml");
        $repository = new TaxRateRepository($client);
        $tax::initialize($repository);

        $this->assertTrue($tax->supports('PL'));
        $this->assertTrue($tax->supports('DE'));
        $this->assertFalse($tax->supports('US'));
    }

    public function testCalculateWithoutSupports()
    {
        $tax = new VatTaxStrategy();
        
        $client = new YamlClient("config/tax_rates.yaml");
        $repository = new TaxRateRepository($client);
        $tax::initialize($repository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Country is not set. Call supports() first.');
        $tax->calculate(100.0);
    }
}