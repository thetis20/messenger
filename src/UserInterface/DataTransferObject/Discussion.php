<?php

namespace App\UserInterface\DataTransferObject;

use App\Domain\Security\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

class Discussion
{
    /** @var string|null  */
    private ?string $name = null;
    /** @var ArrayCollection<User>|null  */
    private ?ArrayCollection $users = null;
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

    public function getUsers(): ?ArrayCollection
    {
        return $this->users;
    }

    public function setUsers(?ArrayCollection $users): void
    {
        $this->users = $users;
    }

}
