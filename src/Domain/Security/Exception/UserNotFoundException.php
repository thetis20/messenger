<?php

namespace App\Domain\Security\Exception;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;

class UserNotFoundException extends InvalidArgumentException implements AssertionFailedException
{
    public const NOT_EXISTS_USERNAME = 5002;

    public function __construct(string $username)
    {
        parent::__construct("The username \"$username\" not exists !", self::NOT_EXISTS_USERNAME);
    }
}
