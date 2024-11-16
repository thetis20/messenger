<?php

namespace App\Domain\Security\Exception;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;

class AlreadyExistingEmailException extends InvalidArgumentException implements AssertionFailedException
{
    public const EXISTING_EMAIL = 5000;

    public function __construct(string $email)
    {
        parent::__construct("This email \"$email\" already used !", self::EXISTING_EMAIL);
    }
}
