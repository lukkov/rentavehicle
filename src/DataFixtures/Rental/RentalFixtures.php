<?php

declare(strict_types=1);

namespace App\DataFixtures\Rental;

use App\DataFixtures\Car\CarFixtures;
use App\DataFixtures\User\UserFixtures;
use App\Entity\Car\Car;
use App\Entity\Rental\Rental;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use \DateTime;

class RentalFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->provideRentals() as [$carReference, $userReference, $startDate, $endDate, $payed]) {
            /** @var Car $car */
            $car = $this->getReference($carReference);
            /** @var User $user */
            $user = $this->getReference($userReference);
            $rental = new Rental($car, $user, $startDate, $endDate, $payed);
            $manager->persist($rental);
        }
        $manager->flush();
    }

    private function provideRentals(): iterable
    {
        yield [CarFixtures::CAR_TWO, UserFixtures::USER_LUKA, new DateTime('midnight'), (new DateTime('midnight'))->modify('+13 day'), false];
        yield [CarFixtures::CAR_TWO, UserFixtures::USER_NENAD, new DateTime('midnight'), (new DateTime('midnight'))->modify('+7 day'), false];
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CarFixtures::class,
        ];
    }
}