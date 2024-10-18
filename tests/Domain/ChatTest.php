<?php

namespace App\Tests\Domain;

use App\Domain\Entity\Message;
use App\Domain\Gateway\MessageGateway;
use App\Domain\Presenter\ChatPresenterInterface;
use App\Domain\Response\ChatResponse;
use App\Domain\UseCase\Chat;
use PHPUnit\Framework\TestCase;

class ChatTest extends TestCase
{
    private MessageGateway $messageGateway;
    private ChatPresenterInterface $presenter;

    protected function setUp(): void
    {
        $this->messageGateway = new class() implements MessageGateway {

            public function add(Message $message): void
            {
            }

            public function findAll(): array
            {
                return array_fill(0, 10, new Message('message'));
            }
        };
        $this->presenter = new class() implements ChatPresenterInterface {
            public array $messages;

            public function present(ChatResponse $chatResponse): void
            {
                $this->messages = array_map(fn(Message $message) => $message->getContent(), $chatResponse->getMessages());
            }
        };
    }

    public function test()
    {
        $chat = new Chat($this->messageGateway);
        $chat->execute($this->presenter);
        $this->assertCount(10, $this->presenter->messages);
    }
}
