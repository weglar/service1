<?php
declare(strict_types=1);

namespace App\Datasource\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class TaxCalculationRequest
{
    #[Assert\NotBlank]
    #[Assert\Type('float')]
    private $amount;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private $country;

    public function __construct($amount, $country)
    {
        $this->amount = $amount;
        $this->country = $country;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}