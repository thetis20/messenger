<?php

namespace App\Domain\Messenger\Entity;

use App\Domain\Messenger\Request\CreateDiscussionRequest;
use App\Domain\Security\Entity\User;
use Symfony\Component\Uid\Uuid;

class Discussion
{
    /** @var Uuid */
    private Uuid $id;
    /** @var string */
    private string $name;
    /** @var array<User> */
    private array $members;

    public static function fromCreation(CreateDiscussionRequest $request): self
    {
        return new self(
            Uuid::v4(),
            $request->getName(),
            $request->getUsers()
        );
    }

    public function __construct(Uuid $id, string $name, array $members)
    {
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMembers(): array
    {
        return $this->members;
    }
}
