<?php

namespace App\Infrastructure\Test\Adapter\Repository;

use App\Domain\Messenger\Entity\Discussion;
use App\Domain\Messenger\Gateway\DiscussionGateway;

class DiscussionRepository implements DiscussionGateway
{
    private array $discussions;

    public function __construct()
    {
        $this->discussions = [];
    }

    public function insert(Discussion $discussion): void
    {
        $this->discussions[] = $discussion;
    }
}
