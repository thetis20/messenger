<?php

namespace App\UserInterface\Presenter;

use Messenger\Domain\Entity\Message;
use Messenger\Domain\Presenter\DeleteMessagePresenterInterface;
use Messenger\Domain\Response\DeleteMessageResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final readonly class DeleteMessagePresenter implements DeleteMessagePresenterInterface
{
    private Message $message;

    public function __construct(private Environment $twig)
    {
    }

    public function present(DeleteMessageResponse $response): void
    {
        $this->message = $response->getMessage();
    }

    public function getResponse(): Response
    {
        return new Response($this->twig->render('message_show.html.twig', [
            'message' => $this->message
        ]));
    }

}
