<?php

namespace App\UserInterface\Presenter;

use App\UserInterface\ViewModel\SendMessageViewModel;
use Messenger\Domain\Presenter\SendMessagePresenterInterface;
use Messenger\Domain\Response\SendMessageResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SendMessagePresenter implements SendMessagePresenterInterface
{
    private SendMessageViewModel $viewModel;

    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function present(SendMessageResponse $response): void
    {
        $this->viewModel = new SendMessageViewModel($response->getDiscussion(), $response->getMessage());
    }

    public function getResponse(): Response
    {
        return new RedirectResponse($this->urlGenerator->generate('discussions_show', [
            'discussionId' => $this->viewModel->getDiscussion()->getId(),
        ]));
    }

}
