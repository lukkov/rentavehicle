<?php

declare(strict_types=1);

namespace App\Entity\Model\Enum;

use InvalidArgumentException;

abstract class AbstractEnum
{
    /** @return array<string, string> */
    public static function getAsFormChoices(): array
    {
        $choices = [];
        foreach (static::getDefinitions() as [$const, $label]) {
            $choices[$label] = $const;
        }

        return $choices;
    }

    public static function getViewFormat(string $key, ?string $default = null): string
    {
        foreach (static::getDefinitions() as [$const, $label]) {
            if ($const === $key) {
                return $label;
            }
        }

        if (null !== $default) {
            return $default;
        }

        throw new InvalidArgumentException(sprintf('Method "%s" is not found in list of definitions.', $key));
    }

    /**
     * @return iterable<array-key, array{0: string, 1: string}>
     */
    abstract protected static function getDefinitions(): iterable;
}