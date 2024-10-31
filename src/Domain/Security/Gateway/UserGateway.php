<?php

namespace App\Domain\Security\Gateway;

interface UserGateway
{
    /**
     * Check if the email is already used by a user
     * @param string $email
     * @return bool
     */
    public function isUniqueEmail(string $email): bool;

    /**
     * Check if the username is already used by a user
     * @param string $username
     * @return bool
     */
    public function isUniqueUsername(string $username): bool;
}
