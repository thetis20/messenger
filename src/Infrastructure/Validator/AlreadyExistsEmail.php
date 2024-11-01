<?php

namespace App\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

class AlreadyExistsEmail extends Constraint
{
    public $message = 'This email is already registered.';
}
