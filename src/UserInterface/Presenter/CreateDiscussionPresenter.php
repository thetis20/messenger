<?php

namespace App\UserInterface\Presenter;

use Messenger\Domain\Entity\UserInterface;
use Messenger\Domain\Presenter\CreateDiscussionPresenterInterface;
use Messenger\Domain\Response\CreateDiscussionResponse;
use App\UserInterface\ViewModel\DiscussionViewModel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateDiscussionPresenter implements CreateDiscussionPresenterInterface
{

    private DiscussionViewModel $viewModel;

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserInterface $user)
    {
    }

    public function present(CreateDiscussionResponse $response): void
    {
        $this->viewModel = DiscussionViewModel::create($response->getDiscussion(), $this->user);
    }

    public function getResponse(): Response
    {
        return new RedirectResponse($this->urlGenerator->generate('discussions_show', [
            'discussionId' => $this->viewModel->getId(),
        ]));
    }

}
