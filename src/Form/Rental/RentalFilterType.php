<?php

declare(strict_types=1);

namespace App\Form\Rental;

use App\Entity\Salon\Salon;
use App\Repository\Salon\SalonRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentalFilterType extends AbstractType
{
    private SalonRepository $salonRepository;

    public function __construct(SalonRepository $salonRepository)
    {
        $this->salonRepository = $salonRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('salon', EntityType::class, [
            'placeholder' => '-- Salon --',
            'class' => Salon::class,
            'choices' => $this->salonRepository->findAll(),
            'group_by' => fn (Salon $salon) => $salon->getCity()->getName(),
            'choice_label' => fn (Salon $salon) => $salon->getName(),
        ]);

        $builder->add('startDate', DateType::class, [
            'format' => 'yyyy/MM/dd',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'filter-rental-start-date'],
        ]);

        $builder->add('endDate', DateType::class, [
            'format' => 'yyyy/MM/dd',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'filter-rental-end-date'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}