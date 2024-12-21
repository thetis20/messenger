<?php

namespace App\UserInterface\Presenter;

use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Presenter\MarkAsPresenterInterface;
use Messenger\Domain\Response\MarkAsDiscussionResponse;
use Symfony\Component\HttpFoundation\Response;

class MarkAsDiscussionPresenter implements MarkAsPresenterInterface
{

    public function present(MarkAsDiscussionResponse $response): void
    {
    }

    public function getResponse(): Response
    {
        return new Response(null, 204);
    }

}
