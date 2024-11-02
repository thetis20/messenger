<?php

namespace App\Domain\Security\Response;

use App\Domain\Messenger\Entity\Discussion;

class CreateDiscussionResponse
{
    private Discussion $discussion;

    public function __construct(Discussion $discussion)
    {
        $this->discussion = $discussion;
    }

    public function getDiscussion(): Discussion
    {
        return $this->discussion;
    }
}
