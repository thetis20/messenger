<?php

namespace App\UserInterface\Controller;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\UserInterface\Form\MessageType;
use App\UserInterface\Presenter\PaginateMessagePresenter;
use App\UserInterface\Presenter\SendMessagePresenter;
use Assert\AssertionFailedException;
use Messenger\Domain\Exception\NotAMemberOfTheDiscussionException;
use Messenger\Domain\Exception\PaginateMessageForbiddenException;
use Messenger\Domain\Exception\SendMessageForbiddenException;
use Messenger\Domain\Request\PaginateMessageRequest;
use Messenger\Domain\Request\SendMessageRequest;
use Messenger\Domain\UseCase\PaginateMessage;
use Messenger\Domain\UseCase\SendMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ShowDiscussionController extends AbstractController
{

    public function __construct(
        private readonly Environment           $twig,
        private readonly DiscussionRepository  $discussionRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly FormFactoryInterface  $formFactory)
    {
    }

    /**
     * @param string $discussionId
     * @param Request $request
     * @param Security $security
     * @param PaginateMessage $paginateMessageUseCase
     * @param SendMessage $sendMessageUseCase
     * @return Response
     * @throws AssertionFailedException
     * @throws NotAMemberOfTheDiscussionException
     * @throws PaginateMessageForbiddenException
     * @throws SendMessageForbiddenException
     */
    public function __invoke(
        string          $discussionId,
        Request         $request,
        Security        $security,
        PaginateMessage $paginateMessageUseCase,
        SendMessage     $sendMessageUseCase,
    ): Response
    {
        $discussion = $this->discussionRepository->findOneById($discussionId);
        $paginateMessagePresenter = new PaginateMessagePresenter($this->twig, $this->getUser());
        $paginateMessageRequest = PaginateMessageRequest::create(
            $this->getUser(),
            $discussion,
            [
                'page' => $request->query->get('page', 1),
            ]
        );
        $paginateMessageUseCase->execute($paginateMessageRequest, $paginateMessagePresenter);

        $form = $this->formFactory->create(MessageType::class)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sendMessageRequest = SendMessageRequest::create(
                $form->getData()->getMessage(),
                $discussion,
                $this->getUser()
            );
            $sendMessagePresenter = new SendMessagePresenter($this->urlGenerator);
            $sendMessageUseCase->execute($sendMessageRequest, $sendMessagePresenter);
            return $sendMessagePresenter->getResponse();
        }

        return $paginateMessagePresenter->getResponse([
            'form' => $form->createView(),
        ]);
    }
}
