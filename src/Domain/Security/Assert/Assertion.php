<?php

namespace App\Domain\Security\Assert;

use App\Domain\Security\Exception\AlreadyExistingEmailException;
use App\Domain\Security\Exception\AlreadyExistingUsernameException;
use App\Domain\Security\Gateway\UserGateway;
use Assert\Assertion as BaseAssertion;

class Assertion extends BaseAssertion
{
    public const EXISTING_EMAIL = 5000;
    public const EXISTING_USERNAME = 5001;

    public static function notExistingEmail(string $email, UserGateway $userGateway): void
    {
        if ($userGateway->emailAlreadyExists($email)) {
            throw new AlreadyExistingEmailException("This email should be unique !", self::EXISTING_EMAIL);
        }
    }

    public static function notExistingUsername(string $username, UserGateway $userGateway): void
    {
        if ($userGateway->usernameAlreadyExists($username)) {
            throw new AlreadyExistingUsernameException("This username should be unique !", self::EXISTING_USERNAME);
        }
    }
}
