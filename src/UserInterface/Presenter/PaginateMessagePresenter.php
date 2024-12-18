<?php

namespace App\UserInterface\Presenter;

use App\UserInterface\ViewModel\PaginateMessageViewModel;
use Messenger\Domain\Entity\UserInterface;
use Messenger\Domain\Presenter\PaginateMessagePresenterInterface;
use Messenger\Domain\Response\PaginateMessageResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PaginateMessagePresenter implements PaginateMessagePresenterInterface
{
    private PaginateMessageViewModel $viewModel;
    private Environment $twig;
    private UserInterface $user;

    public function __construct(Environment $twig, UserInterface $user)
    {
        $this->twig = $twig;
        $this->user = $user;
    }

    public function present(PaginateMessageResponse $response): void
    {
        $this->viewModel = new PaginateMessageViewModel(
            $response->getDiscussion(),
            $response->getMessages(),
            $response->getPage(),
            $response->getLimit(),
            $response->getTotal(),
            $this->user
        );
    }

    public function getViewModel(): PaginateMessageViewModel
    {
        return $this->viewModel;
    }

    public function getResponse(array $context = []): Response
    {
        return new Response($this->twig->render('discussions_show.html.twig', array_merge($context, [
            'vm' => $this->getViewModel(),
        ])));
    }

}
