<?php

namespace App\Domain\Security\Entity;

use App\Domain\Security\Request\RegistrationRequest;
use Symfony\Component\Uid\Uuid;

class User
{
    /** @var Uuid */
    private Uuid $id;
    /** @var string */
    private string $email;
    /** @var string */
    private string $username;
    /** @var string */
    private string $password;

    public static function fromRegistration(RegistrationRequest $request): self
    {
        return new self($request->getEmail(), $request->getUsername(), $request->getPlainPassword());
    }

    public function __construct(string $email, string $username, string $password)
    {
        $this->id = Uuid::v4();
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

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }
}
