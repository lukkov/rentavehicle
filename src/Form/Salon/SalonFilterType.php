<?php

declare(strict_types=1);

namespace App\Form\Salon;

use App\Entity\City\City;
use App\Entity\Salon\Salon;
use App\Repository\City\CityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalonFilterType extends AbstractType
{
    private CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $cities = $this->cityRepository->findAll();

        $builder->add('name', TextType::class);
        $builder->add('city', EntityType::class, [
            'placeholder' => '-- City --',
            'class' => City::class,
            'choices' => $cities,
            'choice_label' => fn (City $city) => $city->getName() . '---' . $city->getPostalCode(),
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