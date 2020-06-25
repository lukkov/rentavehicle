<?php

declare(strict_types=1);

namespace App\Entity\Car\Enum;

use App\Entity\Model\Enum\AbstractEnum;

class ColorEnum extends AbstractEnum
{
    public const RED = '(red)';
    public const BLUE = '(blue)';
    public const WHITE = '(white)';
    public const BLACK = '(black)';
    public const YELLOW = '(yellow)';

    protected static function getDefinitions(): iterable
    {
        yield [self::RED, 'Red'];
        yield [self::BLUE, 'Blue'];
        yield [self::WHITE, 'White'];
        yield [self::BLACK, 'Black'];
        yield [self::YELLOW, 'Yellow'];
    }
}