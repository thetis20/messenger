<?php

namespace App\UserInterface\Presenter;

use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Entity\Message;
use Messenger\Domain\Presenter\ShowDiscussionPresenterInterface;
use Messenger\Domain\Response\ShowDiscussionResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ShowDiscussionPresenter implements ShowDiscussionPresenterInterface
{
    private Discussion $discussion;
    /**
     * @var Message[]
     */
    private array $messages;
    private bool $hasNextPage;
    private ?int $nextPage;

    public function __construct(private readonly Environment $twig)
    {
    }

    public function present(ShowDiscussionResponse $response): void
    {
        $this->discussion = $response->getDiscussion();
        $this->messages = $response->getMessages();
        $this->hasNextPage = $response->hasNextPage();
        $this->nextPage = $response->getNextPage();
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
            'discussion' => $this->discussion,
            'messages' => $this->messages,
            'hasNextPage' => $this->hasNextPage,
            'nextPage' => $this->nextPage,
        ])));
    }

}
