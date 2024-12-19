<?php

namespace App\UserInterface\Presenter;

use App\UserInterface\ViewModel\ShowDiscussionViewModel;
use Messenger\Domain\Entity\UserInterface;
use Messenger\Domain\Presenter\ShowDiscussionPresenterInterface;
use Messenger\Domain\Response\ShowDiscussionResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ShowDiscussionPresenter implements ShowDiscussionPresenterInterface
{
    private ShowDiscussionViewModel $viewModel;
    private Environment $twig;
    private UserInterface $user;

    public function __construct(Environment $twig, UserInterface $user)
    {
        $this->twig = $twig;
        $this->user = $user;
    }

    public function present(ShowDiscussionResponse $response): void
    {
        $this->viewModel = new ShowDiscussionViewModel(
            $response->getDiscussion(),
            $response->getMessages(),
            $response->getPage(),
            $response->getLimit(),
            $response->getTotal(),
            $this->user
        );
    }

    public function getViewModel(): ShowDiscussionViewModel
    {
        return $this->viewModel;
    }

    /**
     * @param array<string, mixed> $context
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getResponse(array $context = []): Response
    {
        return new Response($this->twig->render('discussions_show.html.twig', array_merge($context, [
            'vm' => $this->getViewModel(),
        ])));
    }

}
