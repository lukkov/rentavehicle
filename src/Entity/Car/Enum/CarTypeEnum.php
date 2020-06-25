<?php

declare(strict_types=1);

namespace App\Entity\Car\Enum;

use App\Entity\Model\Enum\AbstractEnum;

class CarTypeEnum extends AbstractEnum
{
    public const SEDAN = '(sedan)';
    public const COUPE = '(coupe)';
    public const SUV = '(suv)';
    public const WAGON = '(wagon)';
    public const HATCHBACK = '(hatchback)';
    public const PICKUP = '(pickup)';

    protected static function getDefinitions(): iterable
    {
        yield [self::SEDAN, 'Sedan'];
        yield [self::COUPE, 'Coupe'];
        yield [self::SUV, 'SUV'];
        yield [self::WAGON, 'Wagon'];
        yield [self::HATCHBACK, 'Hatchback'];
        yield [self::PICKUP, 'Pickup'];
    }
}