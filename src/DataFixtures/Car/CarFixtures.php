<?php

declare(strict_types=1);

namespace App\DataFixtures\Car;

use App\DataFixtures\Car\Feature\FeatureFixtures;
use App\DataFixtures\Car\Model\ModelFixtures;
use App\DataFixtures\Document\DocumentFixtures;
use App\DataFixtures\Salon\SalonFixtures;
use App\Entity\Car\Car;
use App\Entity\Car\Enum\CarTypeEnum;
use App\Entity\Car\Enum\ColorEnum;
use App\Entity\Car\Enum\FuelEnum;
use App\Entity\Car\Enum\NumberOfDoorsEnum;
use App\Entity\Car\Enum\NumberOfSeatsEnum;
use App\Entity\Car\Enum\TransmissionEnum;
use App\Entity\Car\Feature\Feature;
use App\Entity\Car\Model\Model;
use App\Entity\Document\Document;
use App\Entity\Salon\Salon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CarFixtures extends Fixture implements DependentFixtureInterface
{
    public const CAR_ONE = 'car_one_reference';
    public const CAR_TWO = 'car_two_reference';

    public function load(ObjectManager $manager): void
    {
        foreach ($this->provideCars() as [$salonReference, $modelReference, $imageReference, $type, $color, $fuel, $transmission, $numberOfDoors, $numberOfSeats, $pricePerDay, $featured, $featureReferences, $carReference]) {
            /** @var Salon $salon */
            $salon = $this->getReference($salonReference);
            /** @var Model $model */
            $model = $this->getReference($modelReference);
            /** @var Document $image */
            $image = $this->getReference($imageReference);
            $car = new Car($salon, $model, $image, $type, $color, $fuel, $transmission, $numberOfDoors, $numberOfSeats, $pricePerDay, $featured);
            foreach ($featureReferences as $featureReference) {
                /** @var Feature $feature */
                $feature = $this->getReference($featureReference);
                $car->addFeature($feature);
            }
            $manager->persist($car);
            $this->addReference($carReference, $car);
        }
        $manager->flush();
    }

    private function provideCars(): iterable
    {
        yield [SalonFixtures::SALON_MILOJE, ModelFixtures::MODEL_OPEL_ASTRA_J, DocumentFixtures::DOCUMENT_CAR_OPEL_ASTRA_J, CarTypeEnum::HATCHBACK, ColorEnum::WHITE, FuelEnum::DIESEL, TransmissionEnum::MANUAL_SIX_GEARS, NumberOfDoorsEnum::TWO_THREE, NumberOfSeatsEnum::FIVE_SEATS, 20, true, $this->getFeatureReferences(), self::CAR_ONE];
        yield [SalonFixtures::SALON_DRAGISA, ModelFixtures::MODEL_RENAULT_CAPTURE, DocumentFixtures::DOCUMENT_CAR_RENAULT_CAPTURE, CarTypeEnum::SEDAN, ColorEnum::BLACK, FuelEnum::DIESEL, TransmissionEnum::MANUAL_SIX_GEARS, NumberOfDoorsEnum::FOUR_FIVE, NumberOfSeatsEnum::FIVE_SEATS, 30, true, $this->getFeatureReferences(), self::CAR_TWO];
    }

    private function getFeatureReferences(): iterable
    {
        yield FeatureFixtures::TRACTION_CONTROL;
        yield FeatureFixtures::ON_BOARD_COMPUTER;
        yield FeatureFixtures::FOG_LAMP;
        yield FeatureFixtures::ESP;
        yield FeatureFixtures::RADIO;
        yield FeatureFixtures::CD_PLAYER;
        yield FeatureFixtures::XENON_HEADLIGHTS;
        yield FeatureFixtures::ALLOY_WHEELS;
        yield FeatureFixtures::ELECTRIC_SIDE_MIRRORS;
    }

    public function getDependencies(): array
    {
        return [
            SalonFixtures::class,
            ModelFixtures::class,
            FeatureFixtures::class,
            DocumentFixtures::class,
        ];
    }
}