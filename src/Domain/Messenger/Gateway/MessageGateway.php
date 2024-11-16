<?php

namespace App\Domain\Messenger\Gateway;

use App\Domain\Messenger\Entity\Message;

interface MessageGateway
{
    /**
     * Save discussion
     * @param Message $message
     * @return void
     */
    public function insert(Message $message): void;
}
