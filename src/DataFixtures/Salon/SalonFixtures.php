<?php

declare(strict_types=1);

namespace App\DataFixtures\Salon;

use App\DataFixtures\City\CityFixtures;
use App\DataFixtures\Document\DocumentFixtures;
use App\DataFixtures\User\UserFixtures;
use App\Entity\City\City;
use App\Entity\Document\Document;
use App\Entity\Salon\Salon;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SalonFixtures extends Fixture implements DependentFixtureInterface
{
    public const SALON_MILOJE = 'salon_miloje_reference';
    public const SALON_DRAGISA = 'salon_dragisa_reference';

    public function load(ObjectManager $manager): void
    {
        foreach ($this->provideSalons() as [$ownerReference, $cityReference, $imageReference, $address, $name, $description, $email, $phoneNumber, $salonReference]) {
            /** @var User $owner */
            $owner = $this->getReference($ownerReference);
            /** @var City $city */
            $city = $this->getReference($cityReference);
            /** @var Document $image */
            $image = $this->getReference($imageReference);
            $salon = new Salon($owner, $city, $image, $address, $name, $description, $email, $phoneNumber);
            $manager->persist($salon);
            $this->addReference($salonReference, $salon);
        }
        $manager->flush();
    }

    private function provideSalons(): iterable
    {
        yield [UserFixtures::USER_MILOJE, CityFixtures::CITY_BELGRADE, DocumentFixtures::DOCUMENT_SALON_MILOJE, 'Kralja Petra 51', 'Rent a car Miloje i sin', $this->generateDescription(), 'miloje_i_sin@gmail.com', '011 222 33 44', self::SALON_MILOJE];
        yield [UserFixtures::USER_DRAGISA, CityFixtures::CITY_NOVI_SAD, DocumentFixtures::DOCUMENT_SALON_DRAGISA,'Kralja Milutina 7/a', 'Automobili Dragisa', $this->generateDescription(),  'salon_two@gmail.com', '011 555 66 77', self::SALON_DRAGISA];
    }

    public function getDependencies(): iterable
    {
        return [
            UserFixtures::class,
            CityFixtures::class,
            DocumentFixtures::class,
        ];
    }

    private function generateDescription(): string
    {
        return str_repeat('Salon description sample.', 35);
    }
}