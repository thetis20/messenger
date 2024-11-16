<?php

namespace App\Domain\Messenger\Exception;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;

class NotAMemberOfTheDiscussionException extends InvalidArgumentException implements AssertionFailedException
{
    public const CODE = 6000;

    public function __construct()
    {
        parent::__construct('Not a member of the discussion', self::CODE);
    }
}
