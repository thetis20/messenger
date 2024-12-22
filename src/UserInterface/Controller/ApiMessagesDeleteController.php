<?php

namespace App\UserInterface\Controller;

use App\UserInterface\Presenter\DeleteMessagePresenter;
use Messenger\Domain\Exception\DeleteMessageForbiddenException;
use Messenger\Domain\Exception\MessageNotFoundException;
use Messenger\Domain\RequestFactory\DeleteMessageRequestFactory;
use Messenger\Domain\UseCase\DeleteMessage;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class ApiMessagesDeleteController extends BaseController
{

    public function __construct(
        private readonly Environment                 $twig,
        private readonly DeleteMessageRequestFactory $requestFactory)
    {
    }

    /**
     * @param string $messageId
     * @param Request $request
     * @param Security $security
     * @param DeleteMessage $useCase
     * @return Response
     * @throws DeleteMessageForbiddenException
     * @throws MessageNotFoundException
     */
    public function __invoke(
        string        $messageId,
        Request       $request,
        Security      $security,
        DeleteMessage $useCase): Response
    {
        $useCaseRequest = $this->requestFactory->create(
            $this->getCurrentUser(),
            $messageId
        );
        $presenter = new DeleteMessagePresenter($this->twig);
        $useCase->execute($useCaseRequest, $presenter);
        return $presenter->getResponse();
    }
}
