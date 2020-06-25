<?php

declare(strict_types=1);

namespace App\Form\Car;

use App\Entity\Car\Enum\CarTypeEnum;
use App\Entity\Car\Enum\ColorEnum;
use App\Entity\Car\Enum\FuelEnum;
use App\Entity\Car\Enum\NumberOfDoorsEnum;
use App\Entity\Car\Enum\NumberOfSeatsEnum;
use App\Entity\Car\Enum\TransmissionEnum;
use App\Entity\Car\Feature\Feature;
use App\Entity\Car\Model\Model;
use App\Entity\City\City;
use App\Entity\Salon\Salon;
use App\Repository\Car\Feature\FeatureRepository;
use App\Repository\Car\Model\ModelRepository;
use App\Repository\City\CityRepository;
use App\Repository\Salon\SalonRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarFilterType extends AbstractType
{
    private SalonRepository $salonRepository;
    private ModelRepository $modelRepository;
    private FeatureRepository $featureRepository;
    private CityRepository $cityRepository;

    public function __construct(
        SalonRepository $salonRepository,
        ModelRepository $modelRepository,
        FeatureRepository  $featureRepository,
        CityRepository $cityRepository
    ) {
        $this->salonRepository = $salonRepository;
        $this->modelRepository = $modelRepository;
        $this->featureRepository = $featureRepository;
        $this->cityRepository = $cityRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $salons = $this->salonRepository->findAll();
        $models = $this->modelRepository->findAll();
        $features = $this->featureRepository->findAll();

        $builder->add('salon', EntityType::class, [
            'placeholder' => '-- Salon --',
            'class' => Salon::class,
            'choices' => $salons,
            'choice_label' => fn (Salon $salon) => $salon->getName(),
        ]);

        $builder->add('model', EntityType::class, [
            'placeholder' => '-- Model --',
            'class' => Model::class,
            'choices' => $models,
            'choice_label' => fn (Model $model) => $model->getName(),
        ]);

        $builder->add('type', ChoiceType::class, [
            'placeholder' => '-- Type --',
            'choices' => CarTypeEnum::getAsFormChoices(),
        ]);

        $builder->add('color', ChoiceType::class, [
            'placeholder' => '-- Color',
            'choices' => ColorEnum::getAsFormChoices(),
        ]);

        $builder->add('fuel', ChoiceType::class, [
            'placeholder' => '-- Fuel --',
            'choices' => FuelEnum::getAsFormChoices(),
        ]);

        $builder->add('transmission', ChoiceType::class, [
            'placeholder' => '-- Transmission --',
            'choices' => TransmissionEnum::getAsFormChoices(),
        ]);

        $builder->add('numberOfDoors', ChoiceType::class, [
            'placeholder' => '-- Number of doors --',
            'choices' => NumberOfDoorsEnum::getAsFormChoices(),
        ]);

        $builder->add('numberOfSeats', ChoiceType::class, [
            'placeholder' => '-- Number of seats --',
            'choices' => NumberOfSeatsEnum::getAsFormChoices(),
        ]);

        $builder->add('features', ChoiceType::class, [
            'placeholder' => '-- Features --',
            'choices' => $features,
            'choice_label' => fn (Feature $feature) => $feature->getName(),
            'multiple' => true,
            'expanded' => true,
        ]);

        $builder->add('pricePerDayFrom', IntegerType::class);

        $builder->add('pricePerDayTo', IntegerType::class);

        $builder->add('featured', CheckboxType::class);

        $builder->add('city', EntityType::class, [
            'placeholder' => '-- City --',
            'class' => City::class,
            'choices' => $this->cityRepository->findAll(),
            'choice_label' => fn (City $city) => $city->getName(),
        ]);

        $builder->add('availableFrom', DateType::class, [
            'format' => 'yyyy/MM/dd',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'quick-search-available-from'],
        ]);

        $builder->add('availableTo', DateType::class, [
            'format' => 'yyyy/MM/dd',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'quick-search-available-to'],
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