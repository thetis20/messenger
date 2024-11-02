<?php

namespace App\Infrastructure\Security;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Domain\Security\Entity\User as UserDomain;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private UserDomain $user;

    public function __construct(UserDomain $user)
    {
        $this->user = $user;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        return;
    }

    public function getUserIdentifier(): string
    {
        return $this->user->getId();
    }

    public function getPassword(): ?string
    {
        return $this->user->getPassword();
    }
}
