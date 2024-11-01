<?php

namespace App\Infrastructure\Adapter\Repository;

use App\Domain\Security\Gateway\UserGateway;

class UserRepository implements UserGateway
{

    public function emailAlreadyExists(?string $email): bool
    {
        return false;
    }

    public function usernameAlreadyExists(?string $username): bool
    {
        return false;
    }
}
