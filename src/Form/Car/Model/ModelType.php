<?php

declare(strict_types=1);

namespace App\Form\Car\Model;

use App\Entity\Car\Brand\Brand;
use App\Entity\Car\Model\Model;
use App\Repository\Car\Brand\BrandRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class ModelType extends AbstractType
{
    private BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('brand', EntityType::class, [
            'placeholder' => '-- Brand --',
            'class' => Brand::class,
            'choices' => $this->brandRepository->findAll(),
            'choice_label' => fn (Brand $brand) => $brand->getName(),
            'get_value' => fn (Model $model) => $model->getBrand(),
            'update_value' => fn (Brand $brand, Model $model) => $model->setBrand($brand),
        ]);

        $builder->add('name', TextType::class, [
            'get_value' => fn (Model $model) => $model->getName(),
            'update_value' => fn (string $name, Model $model) => $model->setName($name),
            'constraints' => [
                new NotNull(),
                new Length(['min' => 2, 'max' => 20]),
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Model::class,
            'factory' => fn (Brand $brand, string $name) => new Model($brand, $name),
            'constraints' => [new UniqueEntity(['fields' => 'name'])],
        ]);
    }
}