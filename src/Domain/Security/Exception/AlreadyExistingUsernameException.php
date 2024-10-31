<?php

namespace App\Domain\Security\Exception;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;

class AlreadyExistingUsernameException extends InvalidArgumentException implements AssertionFailedException
{
}
