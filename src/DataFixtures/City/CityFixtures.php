<?php

declare(strict_types=1);

namespace App\DataFixtures\City;

use App\Entity\City\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public const CITY_BELGRADE = 'city_belgrade_reference';
    public const CITY_NOVI_SAD = 'city_novi_sad_reference';
    public const CITY_NIS = 'city_nis_reference';

    public function load(ObjectManager $manager): void
    {
        foreach ($this->provideCities() as [$name, $postalCode, $cityReference]) {
            $city = new City($name, $postalCode);
            $manager->persist($city);
            $this->addReference($cityReference, $city);
        }

        $manager->flush();
    }

    private function provideCities(): iterable
    {
        yield ['Belgrade', 11500, self::CITY_BELGRADE];
        yield ['Novi Sad', 21000, self::CITY_NOVI_SAD];
        yield ['Nis', 18000, self::CITY_NIS];
    }
}