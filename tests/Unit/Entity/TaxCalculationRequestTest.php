<?php
declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Datasource\Entity\TaxCalculationRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class TaxCalculationRequestTest extends TestCase
{
    public function testValidRequest()
    {
        // Create a valid TaxCalculationRequest
        $request = new TaxCalculationRequest(100.0, 'FR');

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        $errors = $validator->validate($request);

        $this->assertCount(0, $errors);
    }

    public function testInvalidRequest()
    {
        // Create an invalid TaxCalculationRequest
        $request = new TaxCalculationRequest('abc', '');

        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        $errors = $validator->validate($request);

        $this->assertCount(2, $errors);

        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()] = $error->getMessage();
        }

        $this->assertEquals('This value should be of type float.', $errorMessages['amount']);
        $this->assertEquals('This value should not be blank.', $errorMessages['country']);
    }
}