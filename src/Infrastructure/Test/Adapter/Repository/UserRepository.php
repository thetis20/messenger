<?php

namespace App\Infrastructure\Test\Adapter\Repository;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use Symfony\Component\Uid\Uuid;

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

    public function findOneByUsername(string $username): ?User
    {
        dump('findOneByUsername', $username);
        if ($username === 'username') {
            return new User(
                Uuid::v4(),
                'email@email.com',
                $username,
                password_hash('password', PASSWORD_DEFAULT));
        }
        return null;
    }

    public function findOneById(Uuid|string $id): ?User
    {
        return null;
    }
}
