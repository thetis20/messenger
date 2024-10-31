<?php

namespace App\Domain\Security\Request;

use App\Domain\Security\Assert\Assertion;
use App\Domain\Security\Gateway\UserGateway;

class RegistrationRequest
{
    /** @var string */
    private string $email;
    /** @var string */
    private string $username;
    /** @var string */
    private string $plainPassword;

    /**
     * @param string $email
     * @param string $username
     * @param string $plainPassword
     * @return self
     */
    public static function create(string $email, string $username, string $plainPassword): self
    {
        return new self($email, $username, $plainPassword);
    }

    /**
     * @param string $email
     * @param string $username
     * @param string $plainPassword
     */
    public function __construct(string $email, string $username, string $plainPassword)
    {

        $this->email = $email;
        $this->username = $username;
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
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
        Assertion::notBlank($this->email);
        Assertion::email($this->email);
        Assertion::notExistingEmail($this->email, $userGateway);
        Assertion::notBlank($this->username);
        Assertion::notExistingUsername($this->username, $userGateway);
        Assertion::notBlank($this->plainPassword);
        Assertion::minLength($this->plainPassword, 8);
    }
}
