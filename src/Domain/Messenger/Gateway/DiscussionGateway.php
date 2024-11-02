<?php

namespace App\Domain\Messenger\Gateway;

use App\Domain\Messenger\Entity\Discussion;

interface DiscussionGateway
{
    /**
     * Save discussion
     * @param Discussion $discussion
     * @return void
     */
    public function insert(Discussion $discussion): void;
}
