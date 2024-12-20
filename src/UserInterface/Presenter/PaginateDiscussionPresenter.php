<?php

namespace App\UserInterface\Presenter;

use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Presenter\PaginateDiscussionPresenterInterface;
use Messenger\Domain\Response\PaginateDiscussionResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PaginateDiscussionPresenter implements PaginateDiscussionPresenterInterface
{
    private int $page;
    private int $totalPages;
    /**
     * @var Discussion[]
     */
    private array $discussions;

    public function __construct(private readonly Environment $twig)
    {
    }

    public function present(PaginateDiscussionResponse $response): void
    {
        $this->page = $response->getPage();
        $this->totalPages = $response->getTotalPages();
        $this->discussions = $response->getDiscussions();
    }

    public function getResponse(): Response
    {
        return new Response($this->twig->render('discussions_list.html.twig', [
            'discussions'=>$this->discussions,
            'page'=>$this->page,
            'totalPages'=>$this->totalPages,
        ]));
    }

}
