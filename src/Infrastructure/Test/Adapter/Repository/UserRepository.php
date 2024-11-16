<?php

namespace App\Infrastructure\Test\Adapter\Repository;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use Symfony\Component\Uid\Uuid;

class UserRepository implements UserGateway
{

    public function emailAlreadyExists(?string $email): bool
    {
        $array = array_map(function (User $user) {
            return $user->getEmail();
        }, Data::getInstance()->getUsers());
        return in_array($email, $array);
    }

    public function usernameAlreadyExists(?string $username): bool
    {
        $array = array_map(function (User $user) {
            return $user->getUsername();
        }, Data::getInstance()->getUsers());
        return in_array($username, $array);
    }

    public function register(User $user): void
    {
        $users = Data::getInstance()->getUsers();
        $users[] = $user;
        Data::getInstance()->setUsers($users);
    }

    public function findOneByUsername(string $username): ?User
    {
        $index = array_search($username, array_map(function ($user){
            return $user->getUsername();
        },Data::getInstance()->getUsers()));
        return $index === false ? null : Data::getInstance()->getUsers()[$index];
    }

    public function findOneById(Uuid|string $id): ?User
    {
        $index = array_search($id, array_map(function ($user){
            return $user->getId();
        },Data::getInstance()->getUsers()));
        return $index === false ? null : Data::getInstance()->getUsers()[$index];
    }
}
