<?php

declare(strict_types=1);

namespace App\Form\Car\Model;

use App\Entity\Car\Brand\Brand;
use App\Repository\Car\Brand\BrandRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModelFilterType extends AbstractType
{
    private BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $brands = $this->brandRepository->findAll();
        $builder->add('name', TextType::class);

        $builder->add('brand', EntityType::class, [
            'placeholder' => '-- Brand --',
            'class' => Brand::class,
            'choices' => $brands,
            'choice_label' => fn (Brand $brand) => $brand->getName(),
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