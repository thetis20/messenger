<?php

namespace App\Domain\Security\Assert;

use App\Domain\Security\Exception\AlreadyExistingEmailException;
use App\Domain\Security\Exception\NotAMemberOfTheDiscussionException;
use App\Domain\Security\Gateway\UserGateway;
use Assert\Assertion as BaseAssertion;

class Assertion extends BaseAssertion
{
    public const EXISTING_EMAIL = 5000;
    public const EXISTING_USERNAME = 5001;
    public const NOT_EXISTS_USERNAME = 5002;

    public static function notExistingEmail(string $email, UserGateway $userGateway): void
    {
        if ($userGateway->emailAlreadyExists($email)) {
            throw new AlreadyExistingEmailException("This email \"$email\" already used !", self::EXISTING_EMAIL);
        }
    }

    public static function notExistingUsername(string $username, UserGateway $userGateway): void
    {
        if ($userGateway->usernameAlreadyExists($username)) {
            throw new NotAMemberOfTheDiscussionException("This username \"$username\" already used by a user !", self::EXISTING_USERNAME);
        }
    }

    public static function userNotExists(string $username, UserGateway $userGateway): void
    {
        if (!$userGateway->usernameAlreadyExists($username)) {
            throw new NotAMemberOfTheDiscussionException("The username \"$username\" not exists !", self::NOT_EXISTS_USERNAME);
        }
    }
}
