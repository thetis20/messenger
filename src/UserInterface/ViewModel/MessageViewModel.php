<?php

namespace App\UserInterface\ViewModel;


use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Entity\Member;
use Messenger\Domain\Entity\Message;
use Messenger\Domain\Entity\UserInterface;
use Symfony\Component\Uid\Uuid;

class MessageViewModel extends Message
{
    private UserInterface $currentUser;

    static public function create(Message $message, UserInterface $user): MessageViewModel
    {
        return new self(
            $user,
            $message->getId(),
            $message->getMessage(),
            $message->getAuthor(),
            $message->getDiscussion(['ignoreUnset' => true]),
            $message->getCreatedAt()
        );
    }

    public function __construct(UserInterface $currentUser, Uuid $id, string $message, Member $author, ?Discussion $discussion, \DateTime $createdAt = new \DateTime())
    {
        parent::__construct($id, $message, $author, $discussion, $createdAt);
        $this->currentUser = $currentUser;
    }

    public function isFromYou(): bool
    {
        return $this->getAuthor()->getEmail() === $this->currentUser->getEmail();
    }

    public function getAuthorName(): string
    {
        return $this->getAuthor()->getEmail() === $this->currentUser->getEmail() ? 'you' : $this->getAuthor()->getUsername();
    }

}
