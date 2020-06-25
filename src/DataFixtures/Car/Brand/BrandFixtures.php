<?php

declare(strict_types=1);

namespace App\DataFixtures\Car\Brand;

use App\Entity\Car\Brand\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{
    public const BRAND_BMW = 'bmw_reference';
    public const BRAND_AUDI = 'audi_reference';
    public const BRAND_VOLKSWAGEN = 'volkswagen_reference';
    public const BRAND_OPEL = 'opel_reference';
    public const BRAND_ALFA_ROMEO = 'alfa_romeo_reference';
    public const BRAND_CHEVROLET = 'chevrolet_reference';
    public const BRAND_DACIA = 'dacia_reference';
    public const BRAND_FIAT = 'fiat_reference';
    public const BRAND_HONDA = 'honda_reference';
    public const BRAND_KIA = 'kia_reference';
    public const BRAND_PEUGEOT = 'peugeot_reference';
    public const BRAND_RENAULT = 'renault_reference';
    public const BRAND_SEAT = 'seat_reference';
    public const BRAND_TOYOTA = 'toyota_reference';

    public function load(ObjectManager $manager): void
    {
        foreach ($this->provideBrands() as [$name, $brandReference]) {
            $brand = new Brand($name);
            $manager->persist($brand);
            $this->addReference($brandReference, $brand);
        }

        $manager->flush();
    }

    private function provideBrands(): iterable
    {
        yield ['BMW', self::BRAND_BMW];
        yield ['Audi', self::BRAND_AUDI];
        yield ['Volkswagen', self::BRAND_VOLKSWAGEN];
        yield ['Opel', self::BRAND_OPEL];
        yield ['Alfa Romeo', self::BRAND_ALFA_ROMEO];
        yield ['Chevrolet', self::BRAND_CHEVROLET];
        yield ['Dacia', self::BRAND_DACIA];
        yield ['Fiat', self::BRAND_FIAT];
        yield ['Honda', self::BRAND_HONDA];
        yield ['Kia', self::BRAND_KIA];
        yield ['Peugeot', self::BRAND_PEUGEOT];
        yield ['Renault', self::BRAND_RENAULT];
        yield ['Seat', self::BRAND_SEAT];
        yield ['Toyota', self::BRAND_TOYOTA];
    }
}