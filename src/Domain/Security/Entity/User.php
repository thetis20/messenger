<?php

namespace App\Domain\Security\Entity;

class User
{
    /** @var string */
    private string $email;
    /** @var string */
    private string $username;
    /** @var string */
    private string $password;

    public static function fromRegistration(string $email, string $username, string $plainPassword): self
    {
        return new self($email, $username, $plainPassword);
    }

    public function __construct(string $email, string $username, string $password)
    {
        $this->email = $email;
        $this->username = $username;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
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
    public function getPassword(): string
    {
        return $this->password;
    }
}
