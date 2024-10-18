<?php

namespace App\UserInterface\ViewModel;

/**
 * Class Chat
 * @package App\UserInterface\ViewModel
 */
class ChatViewModel
{
    /**
     * @var string[]
     */
    private array $messages = [];

    /**
     * @param string[] $messages
     */
    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
