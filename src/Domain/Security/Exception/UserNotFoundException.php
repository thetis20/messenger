<?php

namespace App\Domain\Security\Exception;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;

class UserNotFoundException extends InvalidArgumentException implements AssertionFailedException
{
}
