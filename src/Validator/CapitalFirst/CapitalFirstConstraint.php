<?php

declare(strict_types=1);

namespace App\Validator\CapitalFirst;

use Symfony\Component\Validator\Constraint;

class CapitalFirstConstraint extends Constraint
{
    public string $message = 'The string "{{ string }}" needs to starts with capital letter!';
}