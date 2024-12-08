<?php

namespace App\UserInterface\ViewModel;


use Messenger\Domain\Entity\Discussion;

class CreateDiscussionViewModel
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
