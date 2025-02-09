<?php
declare(strict_types=1);

namespace App\Tests\EndToEnd;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaxControllerTest extends WebTestCase
{
    public function testCalculateTaxEndpoint()
    {
        $client = static::createClient();
        $client->request('GET', '/tax?amount=100&country=FR');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }
}