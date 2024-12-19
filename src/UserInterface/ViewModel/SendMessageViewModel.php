<?php

namespace App\UserInterface\ViewModel;


use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Entity\Message;

class SendMessageViewModel
{
    private Discussion $discussion;
    private Message $message;

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
