<?php

namespace App\UserInterface\Presenter;

use App\UserInterface\ViewModel\PaginateDiscussionViewModel;
use Messenger\Domain\Entity\UserInterface;
use Messenger\Domain\Presenter\PaginateDiscussionPresenterInterface;
use Messenger\Domain\Response\PaginateDiscussionResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PaginateDiscussionPresenter implements PaginateDiscussionPresenterInterface
{
    private PaginateDiscussionViewModel $viewModel;
    private Environment $twig;
    private UserInterface $user;

    public function __construct(Environment $twig, UserInterface $user)
    {
        $this->twig = $twig;
        $this->user = $user;
    }

    public function present(PaginateDiscussionResponse $response): void
    {
        $this->viewModel = new PaginateDiscussionViewModel(
            $response->getDiscussions(),
            $response->getPage(),
            $response->getLimit(),
            $response->getTotal(),
            $this->user
        );
    }

    public function getViewModel(): PaginateDiscussionViewModel
    {
        return $this->viewModel;
    }
    public function getResponse(): Response{
        return new Response($this->twig->render('discussions_list.html.twig', [
            'vm' => $this->getViewModel(),
        ]));
    }

}
