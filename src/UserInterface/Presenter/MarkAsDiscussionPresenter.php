<?php

namespace App\UserInterface\Presenter;

use Messenger\Domain\Presenter\MarkAsDiscussionPresenterInterface;
use Messenger\Domain\Response\MarkAsDiscussionResponse;
use Symfony\Component\HttpFoundation\Response;

class MarkAsDiscussionPresenter implements MarkAsDiscussionPresenterInterface
{

    public function present(MarkAsDiscussionResponse $response): void
    {
    }

    public function getResponse(): Response
    {
        return new Response(null, 204);
    }

}
