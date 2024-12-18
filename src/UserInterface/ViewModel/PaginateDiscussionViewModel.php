<?php

namespace App\UserInterface\ViewModel;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Entity\UserInterface;

class PaginateDiscussionViewModel
{
    /** @var DiscussionViewModel[] */
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
        $this->discussions = array_map(function (Discussion $discussion) use ($user) {
            return DiscussionViewModel::create($discussion, $user);
        }, $discussions);
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
        return $this->total ? ceil($this->total / $this->limit) : 1;
    }
}
