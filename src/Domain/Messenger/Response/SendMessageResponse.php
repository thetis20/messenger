<?php

namespace App\Domain\Messenger\Response;

use App\Domain\Messenger\Entity\Discussion;
use App\Domain\Messenger\Entity\Message;

class SendMessageResponse implements ResponseInterface
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
