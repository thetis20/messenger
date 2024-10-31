<?php

namespace App\Domain\Security\Exception;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;

class AlreadyExistingEmailException extends InvalidArgumentException implements AssertionFailedException
{
}
