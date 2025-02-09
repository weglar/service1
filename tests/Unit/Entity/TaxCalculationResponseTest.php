<?php
declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Datasource\Entity\TaxCalculationResponse;
use PHPUnit\Framework\TestCase;

class TaxCalculationResponseTest extends TestCase
{
    public function testToArray()
    {
        $response = new TaxCalculationResponse(100.0, 'FR', ['VAT' => 20.0]);

        $expected = [
            'amount' => 100.0,
            'country' => 'FR',
            'taxes' => ['VAT' => 20.0],
        ];

        $this->assertEquals($expected, $response->toArray());
    }
}