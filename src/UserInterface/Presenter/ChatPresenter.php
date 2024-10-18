<?php

namespace App\UserInterface\Presenter;

use App\Domain\Entity\Message;
use App\Domain\Presenter\ChatPresenterInterface;
use App\Domain\Response\ChatResponse;
use App\UserInterface\ViewModel\ChatViewModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * class ChatPresenter
 * @package App\UserInterface\Presenter
 */
class ChatPresenter implements ChatPresenterInterface
{
    /**
     * @var ChatViewModel
     */
    private ChatViewModel $chatViewModel;

    public function present(ChatResponse $chatResponse): void
    {
        $this->chatViewModel = new ChatViewModel(array_map(fn(Message $message) => $message->getContent(), $chatResponse->getMessages()));
    }

    public function getChatViewModel(): ChatViewModel
    {
        return $this->chatViewModel;
    }
}
