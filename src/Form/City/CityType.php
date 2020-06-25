<?php

declare(strict_types=1);

namespace App\Form\City;

use App\Entity\City\City;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'get_value' => fn (City $city) => $city->getName(),
            'update_value' => fn (string $name, City $city) => $city->setName($name),
            'constraints' => [
                new NotNull(),
                new Length(['min' => 3, 'max' => 30]),
            ]
        ]);

        $builder->add('postalCode', IntegerType::class, [
            'get_value' => fn (City $city) => $city->getPostalCode(),
            'update_value' => fn (int $postalCode, City $city) => $city->setPostalCode($postalCode),
            'constraints' => [
                new NotNull(),
                new Range(['max' => 50000]),
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => City::class,
            'factory' => fn (string $name, int $postalCode) => new City($name, $postalCode),
            'constraints' => [new UniqueEntity(['fields' => ['name', 'postalCode']])],
        ]);
    }
}