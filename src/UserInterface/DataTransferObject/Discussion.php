<?php

namespace App\UserInterface\DataTransferObject;

class Discussion
{
    private ?string $name = null;
    private ?string $usernames = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getUsernames(): ?string
    {
        return $this->usernames;
    }

    public function setUsernames(?string $usernames): void
    {
        $this->usernames = $usernames;
    }
}
