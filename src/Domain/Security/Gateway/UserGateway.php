<?php

namespace App\Domain\Security\Gateway;

use App\Domain\Security\Entity\User;
use Symfony\Component\Uid\Uuid;

interface UserGateway
{
    /**
     * Check if the email is already used by a user
     * @param string|null $email
     * @return bool
     */
    public function emailAlreadyExists(?string $email): bool;

    /**
     * Check if the username is already used by a user
     * @param string|null $username
     * @return bool
     */
    public function usernameAlreadyExists(?string $username): bool;

    /**
     * Save the user
     * @param User $user
     * @return void
     */
    public function register(User $user): void;

    /**
     * @param string $username
     * @return User|null
     */
    public function findOneByUsername(string $username): ?User;

    /**
     * @param Uuid|string $id
     * @return User|null
     */
    public function findOneById(Uuid|string $id): ?User;
}
