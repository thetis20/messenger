<?php

namespace App\UserInterface\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Pagination
{
    public int $totalPages;
    public int $page;

    public function __construct(int $page = 1, int $totalPages = 1)
    {
        $this->page = $page;
        $this->totalPages = $totalPages;
    }

    public function hasPreviousPage(): bool
    {
        return $this->page > 1;
    }

    public function hasNextPage(): bool
    {
        return $this->page < $this->totalPages;
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

    use DefaultActionTrait;
}
