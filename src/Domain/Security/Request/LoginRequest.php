<?php

namespace App\Domain\Security\Request;

use App\Domain\Security\Assert\Assertion;
use App\Domain\Security\Gateway\UserGateway;

class LoginRequest
{
    /** @var string */
    private string $username;
    /** @var string */
    private string $plainPassword;

    /**
     * @param string $username
     * @param string $plainPassword
     * @return self
     */
    public static function create(string $username, string $plainPassword): self
    {
        return new self($username, $plainPassword);
    }

    /**
     * @param string $username
     * @param string $plainPassword
     */
    public function __construct(string $username, string $plainPassword)
    {
        $this->username = $username;
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function validate(UserGateway $userGateway): void
    {
        Assertion::notBlank($this->username);
        Assertion::notBlank($this->plainPassword);
    }
}
