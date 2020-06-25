<?php

declare(strict_types=1);

namespace App\Form\Rental;

use App\Entity\Car\Car;
use App\Entity\Rental\Rental;
use App\Entity\User\User;
use App\Repository\Rental\RentalRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

abstract class AbstractRentalType extends AbstractType
{
    protected Security $security;
    protected RentalRepository $rentalRepository;

    public function __construct(Security $security, RentalRepository $rentalRepository)
    {
        $this->security = $security;
        $this->rentalRepository = $rentalRepository;
    }

    protected function validate(?Rental $rental, ExecutionContextInterface $executionContext): void
    {
        /** @var User|null $user */
        if (!$user = $this->security->getUser()) {
            $executionContext->addViolation('You have to be logged in.');
            return;
        }

        if (!$rental) {
            return;
        }

        [$startDate, $endDate] = [$rental->getStartDate(), $rental->getEndDate()];

        if ($startDate < new DateTime('now')) {
            $executionContext->addViolation('Start date must be greater than today or equals');

            return;
        }

        if ($startDate >= $endDate) {
            $executionContext->addViolation('End date must be greater than start date.');

            return;
        }

        if ($this->rentalRepository->isCarAvailableForPeriod($rental->getCar(), $rental->getStartDate(), $rental->getEndDate(), $rental)) {
            $executionContext->addViolation('This car is not available for selected period.');
        }
    }

    protected function processUsedDates(Car $car): array
    {
        $rentalsForCar = $this->rentalRepository->findRentalsByCar($car);
        $usedDates = [];
        foreach ($rentalsForCar as $rental) {
            $datePeriod = new DatePeriod($rental->getStartDate(), new DateInterval('P1D'), $rental->getEndDate());
            foreach ($datePeriod as $day) {
                $usedDates[] = $day->format('Y-m-d');
            }
        }

        return $usedDates;
    }
}
