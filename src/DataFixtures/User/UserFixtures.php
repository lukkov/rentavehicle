<?php

declare(strict_types=1);

namespace App\DataFixtures\User;

use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserFixtures extends Fixture
{
    public const USER_LUKA = 'user_luka_reference';
    public const USER_NENAD = 'user_nenad_reference';
    public const USER_ZELJKO = 'user_zeljko_reference';
    public const USER_MILOJE = 'user_miloje_reference';
    public const USER_DRAGISA = 'user_dragisa_reference';

    private EncoderFactoryInterface $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->provideUsers() as [$email, $firstName, $lastName, $password, $roles, $userReference]) {
            $encoder = $this->encoderFactory->getEncoder(User::class);
            $user = new User($email, $firstName, $lastName, $encoder->encodePassword($password, null), $roles);
            $manager->persist($user);
            $this->addReference($userReference, $user);
        }

        $manager->flush();
    }

    private function provideUsers(): iterable
    {
        yield ['kovacevic-luka@outlook.com', 'Luka', 'Kovacevic', 'password123!_', ['ROLE_ADMIN'], self::USER_LUKA];
        yield ['nenad@outlook.com', 'Nenad', 'Kojic', 'password123!_', ['ROLE_ADMIN'], self::USER_NENAD];
        yield ['zeljko@outlook.com', 'Zeljko', 'Mitic', 'password123!_', ['ROLE_ADMIN'], self::USER_ZELJKO];
        yield ['miloje@outlook.com', 'Miloje', 'Milivojevic', 'password123!_', ['ROLE_USER'], self::USER_MILOJE];
        yield ['dragica@outlook.com', 'Dragisa', 'Svilarevic', 'password123!_', ['ROLE_USER'], self::USER_DRAGISA];
    }
}