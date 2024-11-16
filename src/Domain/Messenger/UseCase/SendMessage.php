<?php

namespace App\Domain\Messenger\UseCase;

use App\Domain\Messenger\Entity\Message;
use App\Domain\Messenger\Gateway\MessageGateway;
use App\Domain\Messenger\Presenter\SendMessagePresenterInterface;
use App\Domain\Messenger\Request\SendMessageRequest;
use App\Domain\Messenger\Response\SendMessageResponse;

class SendMessage
{
    private MessageGateway $messageGateway;

    public function __construct(MessageGateway $messageGateway)
    {
        $this->messageGateway = $messageGateway;
    }

    public function execute(SendMessageRequest $request, SendMessagePresenterInterface $presenter): void
    {
        $message = Message::fromCreation($request);
        $this->messageGateway->insert($message);
        $presenter->present(new SendMessageResponse($request->getDiscussion(), $message));
    }
}
