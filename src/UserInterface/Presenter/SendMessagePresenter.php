<?php

namespace App\UserInterface\Presenter;

use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Presenter\SendMessagePresenterInterface;
use Messenger\Domain\Response\SendMessageResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SendMessagePresenter implements SendMessagePresenterInterface
{
    private Discussion $discussion;

    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function present(SendMessageResponse $response): void
    {
        $this->discussion = $response->getDiscussion();
    }

    public function getResponse(): Response
    {
        return new RedirectResponse($this->urlGenerator->generate('discussions_show', [
            'discussionId' => $this->discussion->getId(),
        ]));
    }

}
