<?php

namespace App\Domain\UseCase;

use App\Domain\Gateway\MessageGateway;
use App\Domain\Presenter\ChatPresenterInterface;
use App\Domain\Response\ChatResponse;

/**
 * Class Chat
 * @package App\Domain\UseCase
 */
class Chat
{
    private MessageGateway $messageGateway;

    public function __construct(MessageGateway $messageGateway)
    {
        $this->messageGateway = $messageGateway;
    }
    public function execute(ChatPresenterInterface $presenter): ChatPresenterInterface
    {
        $presenter->present(new ChatResponse($this->messageGateway->findAll()));
        return $presenter;
    }
}
