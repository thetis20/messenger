<?php

namespace App\UserInterface\ViewModel;


use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Entity\Member;
use Messenger\Domain\Entity\Message;
use Messenger\Domain\Entity\UserInterface;

class ShowDiscussionViewModel
{
    private Discussion $discussion;
    /** @var Message[] */
    private array $messages;
    /** @var int */
    private int $page;
    /** @var int */
    private int $limit;
    /** @var int */
    private int $total;
    private UserInterface $user;

    /**
     * @param Message[] $messages
     */
    public function __construct(Discussion $discussion, array $messages, int $page, int $limit, int $total, UserInterface $user)
    {
        $this->discussion = DiscussionViewModel::create($discussion, $user);
        $this->messages = array_map(function (Message $message) use ($user) {
            return MessageViewModel::create($message, $user);
        }, $messages);
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $total;
        $this->user = $user;
    }

    public function getDiscussion(): Discussion
    {
        return $this->discussion;
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getTotalPages(): int
    {
        return $this->total ? ceil($this->total / $this->limit) : 1;
    }

    public function hasPreviousPage(): bool
    {
        return $this->page > 1;
    }

    public function hasNextPage(): bool
    {
        return $this->page < $this->getTotalPages();
    }

    public function getNextPage(): ?int
    {
        if (!$this->hasNextPage()) {
            return null;
        }
        return $this->page + 1;
    }

    public function getPreviousPage(): ?int
    {
        if (!$this->hasPreviousPage()) {
            return null;
        }
        return $this->page - 1;
    }

    /**
     * @return Member[]
     */
    public function getOtherMembers(): array
    {
        $members = [];
        foreach ($this->discussion->getDiscussionMembers() as $dm) {
            if ($dm->getMember()->getEmail() === $this->user->getEmail()) {
                continue;
            }
            $members[] = $dm->getMember();
        }
        return $members;
    }

}
