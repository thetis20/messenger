<?php

namespace App\Domain\Security\Exception;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;

class AlreadyExistingUsernameException extends InvalidArgumentException implements AssertionFailedException
{
    public const EXISTING_USERNAME = 5001;

    public function __construct(string $username)
    {
        parent::__construct("This username \"$username\" already used by a user !", self::EXISTING_USERNAME);
    }
}
