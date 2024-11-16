<?php

namespace App\Domain\Messenger\UseCase;

use App\Domain\Messenger\Entity\Message;
use App\Domain\Messenger\Gateway\MessageGateway;
use App\Domain\Messenger\Gateway\NotificationGateway;
use App\Domain\Messenger\Presenter\SendMessagePresenterInterface;
use App\Domain\Messenger\Request\SendMessageRequest;
use App\Domain\Messenger\Response\SendMessageResponse;

class SendMessage
{
    private MessageGateway $messageGateway;
    private NotificationGateway $notificationGateway;

    public function __construct(MessageGateway $messageGateway, NotificationGateway $notificationGateway)
    {
        $this->messageGateway = $messageGateway;
        $this->notificationGateway = $notificationGateway;
    }

    public function execute(SendMessageRequest $request, SendMessagePresenterInterface $presenter): void
    {
        $message = Message::fromCreation($request);
        $this->messageGateway->insert($message);
        foreach($request->getDiscussion()->getMembers() as $member){
            if($member->getId()->toString() !== $request->getAuthor()->getId()->toString()){
                $this->notificationGateway->send('send-message', $member, new SendMessageResponse($request->getDiscussion(), $message));
            }
        }
        $presenter->present(new SendMessageResponse($request->getDiscussion(), $message));
    }
}
