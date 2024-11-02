<?php

namespace App\Domain\Messenger\UseCase;

use App\Domain\Messenger\Entity\Discussion;
use App\Domain\Messenger\Gateway\DiscussionGateway;
use App\Domain\Messenger\Presenter\CreateDiscussionPresenterInterface;
use App\Domain\Messenger\Request\CreateDiscussionRequest;
use App\Domain\Security\Response\CreateDiscussionResponse;

class CreateDiscussion
{
    private DiscussionGateway $discussionGateway;

    public function __construct(DiscussionGateway $discussionGateway)
    {
        $this->discussionGateway = $discussionGateway;
    }

    public function execute(CreateDiscussionRequest $request, CreateDiscussionPresenterInterface $presenter): void
    {
        $request->validate();
        $discussion = Discussion::fromCreation($request);
        $this->discussionGateway->insert($discussion);
        $presenter->present(new CreateDiscussionResponse($discussion));
    }
}
