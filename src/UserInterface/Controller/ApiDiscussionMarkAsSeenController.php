<?php

namespace App\UserInterface\Controller;

use App\UserInterface\Presenter\MarkAsDiscussionPresenter;
use Messenger\Domain\Exception\DiscussionNotFoundException;
use Messenger\Domain\Exception\MarkAsDiscussionForbiddenException;
use Messenger\Domain\Exception\NotAMemberOfTheDiscussionException;
use Messenger\Domain\RequestFactory\MarkAsDiscussionRequestFactory;
use Messenger\Domain\UseCase\MarkAsDiscussion;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ApiDiscussionMarkAsSeenController extends BaseController
{

    public function __construct(private readonly MarkAsDiscussionRequestFactory $requestFactory)
    {
    }

    /**
     * @param string $discussionId
     * @param Request $request
     * @param Security $security
     * @param MarkAsDiscussion $useCase
     * @return JsonResponse
     * @throws DiscussionNotFoundException
     * @throws MarkAsDiscussionForbiddenException
     * @throws NotAMemberOfTheDiscussionException
     */
    public function __invoke(
        string           $discussionId,
        Request          $request,
        Security         $security,
        MarkAsDiscussion $useCase): Response
    {
        $useCaseRequest = $this->requestFactory->create(
            $this->getCurrentUser(),
            $discussionId
        );
        $presenter = new MarkAsDiscussionPresenter();
        $useCase->execute($useCaseRequest, $presenter);
        return $presenter->getResponse();
    }
}
