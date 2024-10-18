<?php

namespace App\Infrastructure\Port;

use App\Domain\Entity\Message;
use App\Domain\Gateway\MessageGateway;

class MessageRepository implements MessageGateway
{

    public function add(Message $message): void
    {
        // TODO: Implement add() method.
    }

    public function findAll(): array
    {
        return [
            new Message('message 1'),
            new Message('message 2'),
            new Message('message 3'),
        ];
    }
}
