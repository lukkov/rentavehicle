<?php

declare(strict_types=1);

namespace App\Validator\Password;

use Symfony\Component\Validator\Constraint;

class PasswordConstraint extends Constraint
{
    public string $message = 'The password must be between 7 and 30 characters length and contain at least one digit, one upper case letter!';
}