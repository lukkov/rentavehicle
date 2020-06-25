<?php

declare(strict_types=1);

namespace App\Form\Car;

use App\Entity\Car\Car;
use App\Entity\Salon\Salon;
use App\Entity\Car\Brand\Brand;
use App\Entity\Car\Model\Model;
use App\Entity\Car\Enum\FuelEnum;
use App\Entity\Document\Document;
use App\Entity\Car\Enum\ColorEnum;
use App\Entity\Car\Feature\Feature;
use App\Form\Document\DocumentType;
use App\Entity\Car\Enum\CarTypeEnum;
use App\Repository\Car\CarRepository;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use App\Entity\Car\Enum\TransmissionEnum;
use App\Repository\Salon\SalonRepository;
use Symfony\Component\Form\FormInterface;
use App\Entity\Car\Enum\NumberOfDoorsEnum;
use App\Entity\Car\Enum\NumberOfSeatsEnum;
use App\Repository\Car\Brand\BrandRepository;
use App\Repository\Car\Model\ModelRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use App\Repository\Car\Feature\FeatureRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

abstract class AbstractCarType extends AbstractType
{
    private const MAX_FEATURED = 4;

    protected CarRepository $carRepository;
    protected SalonRepository $salonRepository;
    protected ModelRepository $modelRepository;
    protected FeatureRepository $featureRepository;
    protected BrandRepository $brandRepository;
    protected Security $security;

    public function __construct(
        CarRepository $carRepository,
        ModelRepository $modelRepository,
        FeatureRepository $featureRepository,
        BrandRepository $brandRepository,
        Security $security
    )
    {
        $this->carRepository = $carRepository;
        $this->modelRepository = $modelRepository;
        $this->brandRepository = $brandRepository;
        $this->featureRepository = $featureRepository;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $brands = $this->brandRepository->findAll();
        $features = $this->featureRepository->findAll();

        $builder->add('salon', EntityType::class, [
            'required' => true,
            'placeholder'  => '-- Salon --',
            'class'        => Salon::class,
            'choices'      => $this->getSalons(),
            'group_by' => fn (Salon $salon) => $salon->getCity()->getName(),
            'choice_label' => fn(Salon $salon) => $salon->getName(),
            'get_value'    => fn(Car $car) => $car->getSalon(),
            'update_value' => fn(Salon $salon, Car $car) => $car->setSalon($salon),
        ]);

        $builder->add('brand', ChoiceType::class, [
            'auto_submit'  => true,
            'choices'      => $brands,
            'choice_label' => fn(Brand $brand) => $brand->getName(),
            'get_value'    => fn(Car $car) => $car->getModel()->getBrand(),
            'update_value' => fn() => null,
            'constraints'  => [
                new NotNull(),
            ],
        ]);

        $builder->add('type', ChoiceType::class, [
            'placeholder'  => '-- Type --',
            'choices'      => CarTypeEnum::getAsFormChoices(),
            'get_value'    => fn(Car $car) => $car->getType(),
            'update_value' => fn(string $type, Car $car) => $car->setType($type),
        ]);

        $builder->add('color', ChoiceType::class, [
            'placeholder'  => '-- Color --',
            'choices'      => ColorEnum::getAsFormChoices(),
            'get_value'    => fn(Car $car) => $car->getColor(),
            'update_value' => fn(string $color, Car $car) => $car->setColor($color),
        ]);

        $builder->add('fuel', ChoiceType::class, [
            'placeholder'  => '-- Fuel --',
            'choices'      => FuelEnum::getAsFormChoices(),
            'get_value'    => fn(Car $car) => $car->getFuel(),
            'update_value' => fn(string $fuel, Car $car) => $car->setFuel($fuel),
        ]);

        $builder->add('transmission', ChoiceType::class, [
            'placeholder'  => '-- Transmission --',
            'choices'      => TransmissionEnum::getAsFormChoices(),
            'get_value'    => fn(Car $car) => $car->getTransmission(),
            'update_value' => fn(string $transmission, Car $car) => $car->setTransmission($transmission),
        ]);

        $builder->add('numberOfDoors', ChoiceType::class, [
            'placeholder'  => '-- Number of doors --',
            'choices'      => NumberOfDoorsEnum::getAsFormChoices(),
            'get_value'    => fn(Car $car) => $car->getNumberOfDoors(),
            'update_value' => fn(string $numberOfDoors, Car $car) => $car->setNumberOfDoors($numberOfDoors),
        ]);

        $builder->add('numberOfSeats', ChoiceType::class, [
            'placeholder'  => '-- Number of seats --',
            'choices'      => NumberOfSeatsEnum::getAsFormChoices(),
            'get_value'    => fn(Car $car) => $car->getNumberOfSeats(),
            'update_value' => fn(string $numberOfSeats, Car $car) => $car->setNumberOfSeats($numberOfSeats),
        ]);

        $builder->add('pricePerDay', IntegerType::class, [
            'get_value'    => fn(Car $car) => $car->getPricePerDay(),
            'update_value' => fn(int $pricePerDay, Car $car) => $car->setPricePerDay($pricePerDay),
        ]);

        $builder->add('features', ChoiceType::class, [
            'placeholder'  => '-- Features --',
            'choices'      => $features,
            'choice_label' => fn(Feature $feature) => $feature->getName(),
            'get_value'    => fn(Car $car) => $car->getFeatures(),
            'add_value'    => fn(Feature $feature, Car $car) => $car->addFeature($feature),
            'remove_value' => fn(Feature $feature, Car $car) => $car->removeFeature($feature),
            'multiple'     => true,
            'expanded'     => true,
        ]);

        $builder->add('featured', CheckboxType::class, [
            'get_value'    => fn(Car $car) => $car->isFeatured(),
            'update_value' => fn(bool $featured, Car $car) => $car->setFeatured($featured),
            'constraints' => [
                new Callback(['callback' => fn (bool $featured, ExecutionContextInterface $executionContext) => $this->validate($featured, $executionContext)])
            ],
        ]);

        $builder->add('image', DocumentType::class, [
            'get_value'    => fn() => null,
            'update_value' => function (?Document $document, Car $car) {
                if ($document) {
                    $car->setImage($document);
                }
            },
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event) {
            /** @var Car|null $model */
            $model = $event->getData();
            $brand = $model ? $model->getModel()->getBrand() : null;
            $this->addModelField($event, $brand);
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $event) use ($brands) {
            /** @var array{brand?: string} $data */
            $data = $event->getData();
            $key = $data['brand'] ?? null;
            $brand = $brands[$key] ?? null;
            $this->addModelField($event, $brand);
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var Car|null $car */
        $car = $form->getData();
        $view->vars['filename'] = $car ? $car->getImageFilename() : null;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
            'factory'    => function (
                FormInterface $form,
                Salon $salon,
                Document $image,
                string $type,
                string $color,
                string $fuel,
                string $transmission,
                string $numberOfDoors,
                string $numberOfSeats,
                int $pricePerDay,
                bool $featured
            ) {

                /** @var Model|null $model */
                $model = $form->has('model') ? $form->get('model')->getData() : null;
                if (!$model) {

                    return null;
                }

                return new Car(
                    $salon,
                    $model,
                    $image,
                    $type,
                    $color,
                    $fuel,
                    $transmission,
                    $numberOfDoors,
                    $numberOfSeats,
                    $pricePerDay,
                    $featured
                );
            }
        ]);
    }

    private function addModelField(FormEvent $event, ?Brand $brand): void
    {
        $form = $event->getForm();
        if (!$brand) {
            $form->remove('model');

            return;
        }

        $models = $this->modelRepository->createQueryBuilder('o')
            ->where('o.brand = :brand')->setParameter('brand', $brand)
            ->getQuery()->getResult();

        $form->add('model', EntityType::class, [
            'placeholder'  => '-- Model --',
            'class'        => Model::class,
            'choices'      => $models,
            'choice_label' => fn(Model $model) => $model->getName(),
            'get_value'    => fn(Car $car) => $car->getModel(),
            'update_value' => fn(Model $model, Car $car) => $car->setModel($model),
            'constraints' => [
                new NotNull(['message' => 'You have to select model.']),
            ],
        ]);
    }

    private function validate(bool $featured, ExecutionContextInterface $executionContext): void
    {
        if (false === $featured) {
            return;
        }
        if ($this->carRepository->getFeaturedCount() >= self::MAX_FEATURED) {
            $executionContext->addViolation(sprintf('There already is %s od more featured cars', self::MAX_FEATURED));
        }
    }

    /** @return Salon[] */
    abstract protected function getSalons(): iterable;
}
