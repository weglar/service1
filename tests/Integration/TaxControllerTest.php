<?php
declare(strict_types=1);

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaxControllerTest extends WebTestCase
{
    public function testCalculateTaxEndpointWithValidInput()
    {
        $client = static::createClient();
        $client->request('GET', '/tax?amount=100.00&country=FR');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('amount', $response);
        $this->assertArrayHasKey('country', $response);
        $this->assertArrayHasKey('taxes', $response);
    }

    public function testCalculateTaxEndpointWithInvalidInput()
    {
        $client = static::createClient();
        $client->request('GET', '/tax?amount=abc&country=');

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('errors', $response);
        // $this->assertArrayHasKey('amount', $response['errors']);
        $this->assertArrayHasKey('country', $response['errors']);
    }
}