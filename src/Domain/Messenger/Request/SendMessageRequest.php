<?php

namespace App\Domain\Messenger\Request;

use App\Domain\Messenger\Entity\Discussion;
use App\Domain\Messenger\Exception\NotAMemberOfTheDiscussionException;
use App\Domain\Security\Assert\Assertion;
use App\Domain\Security\Entity\User;

class SendMessageRequest
{
    private string $message;
    private User $author;
    private Discussion $discussion;

    public static function create(string $message, Discussion $discussion,  User $author): SendMessageRequest
    {

        Assertion::notBlank($message);
        if (!$discussion->isMember($author)) {
            throw new NotAMemberOfTheDiscussionException();
        }
        return new SendMessageRequest($message, $author, $discussion);
    }

    public function __construct(string $message, User $author, Discussion $discussion)
    {
        $this->message = $message;
        $this->author = $author;
        $this->discussion = $discussion;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getDiscussion(): Discussion
    {
        return $this->discussion;
    }
}
