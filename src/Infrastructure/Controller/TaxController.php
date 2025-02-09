<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Datasource\Entity\TaxCalculationRequest;
use App\Domain\Service\TaxCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Exception\ValidationException;

class TaxController extends AbstractController
{
    public function __construct(
        private readonly TaxCalculatorService $service,
    ) {}

    #[Route('/tax', name: 'calculate_tax', methods: ['GET'])]
    public function calculateTax(Request $request): JsonResponse
    {
        $amount = (float) $request->query->get('amount');
        $country = $request->query->get('country');

        try {
            $data = new TaxCalculationRequest($amount, $country);

            $response = $this->service->calculateTax($data);

            return new JsonResponse($response->toArray());
        } catch (ValidationException $e) {
            return new JsonResponse(['errors' => $e->getErrors()], $e->getCode());
        }
    }
}