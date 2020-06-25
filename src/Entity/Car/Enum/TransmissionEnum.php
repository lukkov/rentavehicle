<?php

declare(strict_types=1);

namespace App\Entity\Car\Enum;

use App\Entity\Model\Enum\AbstractEnum;

class TransmissionEnum extends AbstractEnum
{
    public const MANUAL_FOUR_GEARS = '(manual-four-gears)';
    public const MANUAL_FIVE_GEARS = '(manual-five-gears)';
    public const MANUAL_SIX_GEARS = '(manual-six-gears)';
    public const AUTOMATIC = '(automatic)';
    public const SEMI_AUTOMATIC = '(semi-automatic)';

    protected static function getDefinitions(): iterable
    {
        yield [self::MANUAL_FOUR_GEARS, 'Manual 4 gears'];
        yield [self::MANUAL_FIVE_GEARS, 'Manual 5 gears'];
        yield [self::MANUAL_SIX_GEARS, 'Manual 6 gears'];
        yield [self::AUTOMATIC, 'Automatic'];
        yield [self::SEMI_AUTOMATIC, 'Semi-automatic'];
    }
}