<?php

declare(strict_types=1);

namespace App\Form\Rental;

use DateTime;
use App\Entity\Car\Car;
use App\Entity\User\User;
use App\Entity\Rental\Rental;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RentCarType extends AbstractRentalType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Car $car */
        $car = $options['car'];
        $usedDates = $this->processUsedDates($car);

        $builder->add('startDate', DateType::class, [
            'format' => 'yyyy/MM/dd',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'rental-start-date', 'data-used-dates' => json_encode($usedDates)],
            'get_value' => fn(Rental $rental) => $rental->getStartDate(),
            'update_value' => fn(DateTime $dateTime, Rental $rental) => $rental->setStartDate($dateTime),
            'constraints' => [
                new NotNull(),
            ]
        ]);

        $builder->add('endDate', DateType::class, [
            'format' => 'yyyy/MM/dd',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'rental-start-date', 'data-used-dates' => json_encode($usedDates)],
            'get_value' => fn(Rental $rental) => $rental->getEndDate(),
            'update_value' => fn(DateTime $dateTime, Rental $rental) => $rental->setEndDate($dateTime),
            'constraints' => [
                new NotNull(),
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('car');
        $resolver->setAllowedTypes('car', Car::class);
        $resolver->setDefault('constraints', [
            new Callback(['callback' => fn (?Rental $rental, ExecutionContextInterface $executionContext) => $this->validate($rental, $executionContext)]),
        ]);

        $resolver->setNormalizer('factory', function (Options $options) {
            /** @var Car $car */
            $car = $options['car'];

            /** @var User|null $user */
            if (!$user = $this->security->getUser()) {
                return fn() => null;
            }

            return fn(DateTime $startDate, DateTime $endDate) => new Rental($car, $user, $startDate, $endDate);
        });
    }
}
