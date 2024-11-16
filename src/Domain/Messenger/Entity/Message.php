<?php

namespace App\Domain\Messenger\Entity;

use App\Domain\Messenger\Request\CreateDiscussionRequest;
use App\Domain\Messenger\Request\SendMessageRequest;
use App\Domain\Security\Entity\User;
use Symfony\Component\Uid\Uuid;

class Message
{
    /** @var Uuid */
    private Uuid $id;
    /** @var User  */
    private User $author;
    /** @var string  */
    private string $message;

    public static function fromCreation(SendMessageRequest $request): self
    {
        return new self(
            Uuid::v4(),
            $request->getMessage(),
            $request->getAuthor()
        );
    }

    public function __construct(Uuid $id, string $message, User $author)
    {
        $this->id = $id;
        $this->message = $message;
        $this->author = $author;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}