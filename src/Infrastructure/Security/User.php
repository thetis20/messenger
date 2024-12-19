<?php

namespace App\Infrastructure\Security;

final class User implements \Symfony\Component\Security\Core\User\UserInterface, \Messenger\Domain\Entity\UserInterface
{
    /**
     * @param string $userIdentifier
     * @param string $email
     * @param string $fullname
     * @param string[] $roles
     */
    public function __construct(
        private readonly string $userIdentifier,
        private readonly string $email,
        private readonly string $fullname,
        private readonly array  $roles,
    )
    {
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): null
    {
        return null;
    }

    public function getSalt(): null
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->userIdentifier;
    }

    public function getUsername(): string
    {
        throw new \BadMethodCallException('Deprecated, should not be called');
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function getUsualName(): string
    {
        return $this->fullname;
    }
}
