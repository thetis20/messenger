<?php

namespace App\Infrastructure\Test\Adapter\Repository;

use App\Domain\Messenger\Entity\Message;
use App\Domain\Messenger\Gateway\MessageGateway;

class MessageRepository implements MessageGateway
{

    public function insert(Message $message): void
    {
        $messages = Data::getInstance()->getMessages();
        $messages[] = $message;
        Data::getInstance()->setMessages($messages);
    }
}
