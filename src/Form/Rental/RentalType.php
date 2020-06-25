<?php

declare(strict_types=1);

namespace App\Form\Rental;

use App\Entity\Car\Car;
use App\Entity\Rental\Rental;
use App\Entity\User\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \DateTime;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RentalType extends AbstractRentalType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Rental|null $rental */
        $rental = $options['data'];
        $usedDates = $rental ? json_encode($this->processUsedDates($rental->getCar())) : [];

        $builder->add('startDate', DateType::class, [
            'get_value' => fn (Rental $rental) => $rental->getStartDate(),
            'update_value' => fn (DateTime $startDate, Rental $rental) => $rental->setStartDate($startDate),
            'widget' => 'single_text',
            'html5' => false,
            'format' => 'yyyy/MM/dd',
            'attr' => ['class' => 'rental-start-date', 'data-used-dates' => json_encode($usedDates)],
        ]);

        $builder->add('endDate', DateType::class, [
            'get_value' => fn (Rental $rental) => $rental->getEndDate(),
            'update_value' => fn (DateTime $endDate, Rental $rental) => $rental->setEndDate($endDate),
            'widget' => 'single_text',
            'html5' => false,
            'format' => 'yyyy/MM/dd',
            'attr' => ['class' => 'rental-end-date', 'data-used-dates' => json_encode($usedDates)],
        ]);

        $builder->add('payed', CheckboxType::class, [
            'get_value' => fn (Rental $rental) => $rental->isPayed(),
            'update_value' => fn (bool $payed, Rental $rental) => $rental->setPayed($payed),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $resolver->setDefaults([
            'data_class' => Rental::class,
            'factory' => fn (Car $car, DateTime $startDate, DateTime $endDate, bool $payed) => new Rental($car, $user, $startDate, $endDate, $payed),
            'constraints' => [
                new Callback(['callback' => fn (?Rental $rental, ExecutionContextInterface $executionContext) => $this->validate($rental, $executionContext)]),
            ],
        ]);
    }
}