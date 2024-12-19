<?php

namespace App\UserInterface\Controller;

use App\UserInterface\Form\MessageType;
use App\UserInterface\Presenter\ShowDiscussionPresenter;
use App\UserInterface\Presenter\SendMessagePresenter;
use Assert\AssertionFailedException;
use Messenger\Domain\Exception\DiscussionNotFoundException;
use Messenger\Domain\Exception\NotAMemberOfTheDiscussionException;
use Messenger\Domain\Exception\ShowDiscussionForbiddenException;
use Messenger\Domain\Exception\SendMessageForbiddenException;
use Messenger\Domain\RequestFactory\SendMessageRequestFactory;
use Messenger\Domain\RequestFactory\ShowDiscussionRequestFactory;
use Messenger\Domain\UseCase\ShowDiscussion;
use Messenger\Domain\UseCase\SendMessage;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ShowDiscussionController extends BaseController
{

    public function __construct(
        private readonly Environment                  $twig,
        private readonly UrlGeneratorInterface        $urlGenerator,
        private readonly FormFactoryInterface         $formFactory,
        private readonly ShowDiscussionRequestFactory $showDiscussionRequestFactory,
        private readonly SendMessageRequestFactory    $sendMessageRequestFactory,)
    {
    }

    /**
     * @param string $discussionId
     * @param Request $request
     * @param Security $security
     * @param ShowDiscussion $showDiscussionUseCase
     * @param SendMessage $sendMessageUseCase
     * @return Response
     * @throws AssertionFailedException
     * @throws NotAMemberOfTheDiscussionException
     * @throws SendMessageForbiddenException
     * @throws ShowDiscussionForbiddenException
     * @throws DiscussionNotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(
        string         $discussionId,
        Request        $request,
        Security       $security,
        ShowDiscussion $showDiscussionUseCase,
        SendMessage    $sendMessageUseCase,
    ): Response
    {
        $showDiscussionPresenter = new ShowDiscussionPresenter($this->twig, $this->getCurrentUser());
        $showDiscussionRequest = $this->showDiscussionRequestFactory->create(
            $this->getCurrentUser(),
            $discussionId,
            [
                'page' => $request->query->get('page', 1),
            ]
        );
        $showDiscussionUseCase->execute($showDiscussionRequest, $showDiscussionPresenter);

        $form = $this->formFactory->create(MessageType::class)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sendMessageRequest = $this->sendMessageRequestFactory->create(
                $this->getCurrentUser(),
                $discussionId,
                $form->getData()->getMessage()
            );
            $sendMessagePresenter = new SendMessagePresenter($this->urlGenerator);
            $sendMessageUseCase->execute($sendMessageRequest, $sendMessagePresenter);
            return $sendMessagePresenter->getResponse();
        }

        return $showDiscussionPresenter->getResponse([
            'form' => $form->createView(),
        ]);
    }
}
