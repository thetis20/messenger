<?php

namespace App\Domain\Security\Assert;

use App\Domain\Security\Exception\AlreadyExistingEmailException;
use App\Domain\Security\Exception\AlreadyExistingUsernameException;
use App\Domain\Security\Exception\UserNotFoundException;
use App\Domain\Security\Gateway\UserGateway;
use Assert\Assertion as BaseAssertion;

class Assertion extends BaseAssertion
{

    public static function notExistingEmail(string $email, UserGateway $userGateway): void
    {
        if ($userGateway->emailAlreadyExists($email)) {
            throw new AlreadyExistingEmailException($email);
        }
    }

    public static function notExistingUsername(string $username, UserGateway $userGateway): void
    {
        if ($userGateway->usernameAlreadyExists($username)) {
            throw new AlreadyExistingUsernameException($username);
        }
    }

    public static function userNotExists(string $username, UserGateway $userGateway): void
    {
        if (!$userGateway->usernameAlreadyExists($username)) {
            throw new UserNotFoundException($username);
        }
    }
}
