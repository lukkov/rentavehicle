<?php

declare(strict_types=1);

namespace App\Form\Car\Feature;

use App\Entity\Car\Feature\Feature;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class FeatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'get_value' => fn (Feature $feature) => $feature->getName(),
            'update_value' => fn (string $name, Feature $feature) => $feature->setName($name),
            'constraints' => [
                new NotNull(),
                new Length(['min' => 3, 'max' => 30]),
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feature::class,
            'factory' => fn (string $name) => new Feature($name),
            'constraints' => [new UniqueEntity(['fields' => 'name'])],
        ]);
    }
}