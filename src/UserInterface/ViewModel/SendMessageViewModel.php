<?php

namespace App\UserInterface\ViewModel;


use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Entity\Message;
use Messenger\Domain\Entity\UserInterface;

class SendMessageViewModel
{
    private Discussion $discussion;

    public function __construct(Discussion $discussion, Message $message)
    {
        $this->discussion = $discussion;
        $this->message = $message;
    }

    public function getDiscussion(): Discussion
    {
        return $this->discussion;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}
