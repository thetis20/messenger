<?php

namespace App\Infrastructure\Test\Adapter\Repository;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use Symfony\Component\Uid\Uuid;

class UserRepository implements UserGateway
{
    private array $users;
    public function __construct()
    {
        $this->users = [
            new User(
                Uuid::v4(),
                'username@email.com',
                'username',
                password_hash('password', PASSWORD_DEFAULT)),
            new User(
                Uuid::v4(),
                'username1@email.com',
                'username1',
                password_hash('password', PASSWORD_DEFAULT))
        ];
    }

    public function emailAlreadyExists(?string $email): bool
    {
        return in_array($email, ['used@email.com']);
    }

    public function usernameAlreadyExists(?string $username): bool
    {
        return in_array($username, ['used-username', 'username', 'username1']);
    }

    public function register(User $user): void
    {
        // do nothing
    }

    public function findOneByUsername(string $username): ?User
    {
        $index = array_search($username, array_map(function ($user){
            return $user->getUsername();
        },$this->users));
        return $index === false ? null : $this->users[$index];
    }

    public function findOneById(Uuid|string $id): ?User
    {
        return null;
    }
}
