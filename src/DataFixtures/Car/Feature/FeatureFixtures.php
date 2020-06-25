<?php

declare(strict_types=1);

namespace App\DataFixtures\Car\Feature;

use App\Entity\Car\Feature\Feature;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FeatureFixtures extends Fixture
{
    public const ABS = 'abs_reference';
    public const ALLOY_WHEELS = 'alloy_wheels_reference';
    public const ARM_REST = 'arm_rest_reference';
    public const XENON_HEADLIGHTS = 'xenon_headlights_reference';
    public const CD_PLAYER = 'cd_player_reference';
    public const RADIO = 'radio_reference';
    public const CENTRAL_LOCKING = 'central_locking_reference';
    public const ELECTRIC_SIDE_MIRRORS = 'electric_side_mirrors_reference';
    public const ESP = 'esp_reference';
    public const FOG_LAMP = 'fog_lamp_reference';
    public const LEATHER_STEERING_WHEEL = 'leather_steering_wheel_reference';
    public const LEATHER_SEATS = 'leather_seats_reference';
    public const ON_BOARD_COMPUTER = 'on_board_computer_reference';
    public const SUNROOF = 'sunroof_reference';
    public const TRACTION_CONTROL = 'traction_control_reference';

    public function load(ObjectManager $manager): void
    {
        foreach ($this->provideFeatures() as [$name, $featureReference]) {
            $feature = new Feature($name);
            $manager->persist($feature);
            $this->addReference($featureReference, $feature);
        }

        $manager->flush();
    }

    private function provideFeatures(): iterable
    {
        yield ['ABS', self::ABS];
        yield ['Alloy wheels', self::ALLOY_WHEELS];
        yield ['Arm rest', self::ARM_REST];
        yield ['Xenon headlights', self::XENON_HEADLIGHTS];
        yield ['CD player', self::CD_PLAYER];
        yield ['Radio', self::RADIO];
        yield ['Central locking', self::CENTRAL_LOCKING];
        yield ['Electric side mirrors', self::ELECTRIC_SIDE_MIRRORS];
        yield ['ESP', self::ESP];
        yield ['Fog lamp', self::FOG_LAMP];
        yield ['Leather steering wheel', self::LEATHER_STEERING_WHEEL];
        yield ['Leather seats', self::LEATHER_SEATS];
        yield ['On-board computer', self::ON_BOARD_COMPUTER];
        yield ['Sunroof', self::SUNROOF];
        yield ['Traction control', self::TRACTION_CONTROL];
    }
}