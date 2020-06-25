<?php

declare(strict_types=1);

namespace App\Entity\Car\Enum;

use App\Entity\Model\Enum\AbstractEnum;

class FuelEnum extends AbstractEnum
{
    public const PETROL = '(petrol)';
    public const PETROL_TNG = '(petrol-tng)';
    public const CNG = '(cng)';
    public const DIESEL = '(diesel)';

    protected static function getDefinitions(): iterable
    {
        yield [self::PETROL, 'Petrol'];
        yield [self::PETROL_TNG, 'Petrol + TNG'];
        yield [self::CNG, 'CNG'];
        yield [self::DIESEL, 'Diesel'];
    }
}