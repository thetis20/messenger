<?php

namespace App\Infrastructure\Security\Jwt;

use stdClass;

final readonly class IdTokenData
{
    /**
     * @param int $exp
     * @param string $subject
     * @param string $email
     * @param string $username
     * @param string $name
     * @param string[] $roles
     */
    public function __construct(
        private int    $exp,
        private string $subject,
        private string $email,
        private string $username,
        private string $name,
        private array  $roles,
    )
    {
    }

    public function getExpires(): int
    {
        return $this->exp;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
