<?php

declare(strict_types=1);

namespace App\Entity\Car\Enum;

use App\Entity\Model\Enum\AbstractEnum;

class NumberOfDoorsEnum extends AbstractEnum
{
    public const TWO_THREE = '(2/3)';
    public const FOUR_FIVE = '(4/5)';

    protected static function getDefinitions(): iterable
    {
        yield [self::TWO_THREE, '2/3'];
        yield [self::FOUR_FIVE, '4/5'];
    }
}