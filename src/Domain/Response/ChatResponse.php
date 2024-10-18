<?php

namespace App\Domain\Response;

use App\Domain\Entity\Message;

/**
 * class ChatResponse
 * @package App\Domain\Response
 */
class ChatResponse
{
    /**
     * @var array
     */
    private array $messages = [];

    /**
     * @param Message[] $messages
     */
    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
