<?php

namespace App\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

class AlreadyExistsUsername extends Constraint
{
    public $message = 'This username is already registered.';
}
