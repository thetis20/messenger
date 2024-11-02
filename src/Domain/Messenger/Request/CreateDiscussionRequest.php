<?php

namespace App\Domain\Messenger\Request;

use App\Domain\Security\Assert\Assertion;
use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class CreateDiscussionRequest
{
    private string $name;
    private string $usernames;
    /** @var array<User> */
    private array $users;
    private UserGateway $userGateway;

    /**
     * @param string $name
     * @param string $usernames
     * @param UserGateway $userGateway
     */
    public function __construct(string $name, string $usernames, UserGateway $userGateway)
    {
        $this->name = $name;
        $this->usernames = $usernames;
        $this->userGateway = $userGateway;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsers(): array
    {
        return  $this->users;
    }

    public function validate(): void
    {
        Assertion::notBlank($this->name);
        Assertion::notBlank($this->usernames);
        foreach (explode(';', $this->usernames) as $username) {
            Assertion::userNotExists(trim($username), $this->userGateway);
        }
        $this->users = [];
        foreach (explode(';', $this->usernames) as $username) {
            $user =$this->userGateway->findOneByUsername(trim($username));
            if(!$user){
                throw new UserNotFoundException();
            }
            $this->users[] = $user;
        }
    }
}
