<?php

namespace App\UserInterface\ViewModel;


use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Entity\UserInterface;

class PaginateDiscussionViewModel
{
    /** @var Discussion[] */
    private array $discussions;
    /** @var int */
    private int $page;
    /** @var int */
    private int $limit;
    /** @var int */
    private int $total;
    private UserInterface $user;

    /**
     * @param Discussion[] $discussions
     */
    public function __construct(array $discussions, int $page, int $limit, int $total, UserInterface $user)
    {
        $this->discussions = $discussions;
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $total;
        $this->user = $user;
    }

    public function getDiscussions(): array
    {
        return $this->discussions;
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
        return ceil($this->total / $this->limit);
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

    public function getOtherMembers(Discussion $discussion): array
    {
        $members = [];
        foreach ($discussion->getDiscussionMembers() as $dm) {
            if($dm->getMember()->getEmail() === $this->user->getEmail()) {
                continue;
            }
            $members[] = $dm->getMember();
        }
        return $members;
    }

    public function isSeen(Discussion $discussion):bool{
        foreach ($discussion->getDiscussionMembers() as $dm) {
            if($dm->getMember()->getEmail() === $this->user->getEmail()) {
               return $dm->isSeen();
            }
        }
        return false;
    }
}
