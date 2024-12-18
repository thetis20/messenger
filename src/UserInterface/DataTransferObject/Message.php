<?php

namespace App\UserInterface\DataTransferObject;

class Message
{
    /** @var string|null */
    private ?string $message = null;

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}
