<?php

declare(strict_types=1);

namespace App\Form\Car\Brand;

use App\Entity\Car\Brand\Brand;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'get_value' => fn (Brand $brand) => $brand->getName(),
            'update_value' => fn (string $name, Brand $brand) => $brand->setName($name),
            'constraints' => [
                new NotNull(),
                new Length(['min' => 3, 'max' => 20]),
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Brand::class,
            'factory' => fn (string $name) => new Brand($name),
            'constraints' => [new UniqueEntity(['fields' => 'name'])],
        ]);
    }
}