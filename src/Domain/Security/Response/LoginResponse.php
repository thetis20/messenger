<?php

namespace App\Domain\Security\Response;

use App\Domain\Security\Entity\User;

class LoginResponse
{
    private ?User $user;
    private bool $passwordValid;

    public function __construct(?User $user, bool $passwordValid)
    {
        $this->user = $user;
        $this->passwordValid = $passwordValid;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function isPasswordValid(): bool
    {
        return $this->passwordValid;
    }
}
