<?php

namespace App\UserInterface\Twig\Components;

use Messenger\Domain\Entity\UserInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Message
{
    public \Messenger\Domain\Entity\Message $message;
    public UserInterface $user;

    public function isFromYou(): bool
    {
        return $this->message->getAuthor()->getEmail() === $this->user->getEmail();
    }

    public function getAuthorName(): string
    {
        return $this->message->getAuthor()->getEmail() === $this->user->getEmail() ? 'you' : $this->message->getAuthor()->getUsername();
    }

    public function canDelete(): bool
    {
        return !$this->message->isDeleted() && $this->message->getAuthor()->getEmail() === $this->user->getEmail();
    }

    use DefaultActionTrait;
}
