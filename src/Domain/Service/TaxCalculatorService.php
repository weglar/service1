<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Datasource\Entity\TaxCalculationRequest;
use App\Datasource\Entity\TaxCalculationResponse;
use App\Exception\ValidationException;
use App\Domain\UseCase\TaxStrategyInitializerUseCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class TaxCalculatorService
{
    private iterable $taxHandlers;

    public function __construct(
        iterable $taxHandlers,
        private readonly ValidatorInterface $validator,
        private readonly TaxStrategyInitializerUseCase $taxStrategyInitializerUseCase
    ) {
        $this->taxHandlers = $taxHandlers;

        // Ensure each tax strategy is initialized
        $this->taxStrategyInitializerUseCase
            ->execute($this->taxHandlers);
    }

    public function calculateTax(TaxCalculationRequest $data): TaxCalculationResponse
    {
        $errors = $this->validator->validate($data);

        if (count($errors) > 0) {
            throw new ValidationException($this->formatValidationErrors($errors));
        }

        $taxes = [];
        foreach ($this->taxHandlers as $handler) {
            if ($handler->supports($data->getCountry())) {
                $taxes[get_class($handler)] = $handler->calculate($data->getAmount());
            }
        }

        return new TaxCalculationResponse($data->getAmount(), $data->getCountry(), $taxes);
    }

    private function formatValidationErrors(ConstraintViolationListInterface $errors): array
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()] = $error->getMessage();
        }
        return $errorMessages;
    }
}