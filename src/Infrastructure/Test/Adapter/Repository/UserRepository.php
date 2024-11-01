<?php

namespace App\Infrastructure\Test\Adapter\Repository;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;

class UserRepository implements UserGateway
{

    public function emailAlreadyExists(?string $email): bool
    {
        return in_array($email, ['used@email.com']);
    }

    public function usernameAlreadyExists(?string $username): bool
    {
        return in_array($username, ['used-username']);
    }

    public function register(User $user): void
    {
        // do nothing
    }
}
