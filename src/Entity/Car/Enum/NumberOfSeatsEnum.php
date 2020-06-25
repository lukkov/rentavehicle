<?php

declare(strict_types=1);

namespace App\Entity\Car\Enum;

use App\Entity\Model\Enum\AbstractEnum;

class NumberOfSeatsEnum extends AbstractEnum
{
    public const TWO_SEATS = '(two_seats)';
    public const THREE_SEATS = '(three_seats)';
    public const FOUR_SEATS = '(four_seats)';
    public const FIVE_SEATS = '(five_seats)';
    public const SIX_SEATS = '(six_seats)';
    public const SEVEN_SEATS = '(seven_seats)';
    public const EIGHT_SEATS = '(eight_seats)';

    protected static function getDefinitions(): iterable
    {
        yield [self::TWO_SEATS, '2 seats'];
        yield [self::THREE_SEATS, '3 seats'];
        yield [self::FOUR_SEATS, '4 seats'];
        yield [self::FIVE_SEATS, '5 seats'];
        yield [self::SIX_SEATS, '6 seats'];
        yield [self::SEVEN_SEATS, '7 seats'];
        yield [self::EIGHT_SEATS, '8 seats'];
    }
}