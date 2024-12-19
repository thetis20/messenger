<?php

namespace App\UserInterface\Controller;

use Messenger\Domain\Exception\PaginateDiscussionForbiddenException;
use App\UserInterface\Presenter\PaginateDiscussionPresenter;
use Messenger\Domain\RequestFactory\PaginateDiscussionRequestFactory;
use Messenger\Domain\UseCase\PaginateDiscussion;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class PaginateDiscussionController extends BaseController
{

    public function __construct(
        private readonly Environment                      $twig,
        private readonly PaginateDiscussionRequestFactory $requestFactory)
    {
    }

    /**
     * @param Request $request
     * @param Security $security
     * @param PaginateDiscussion $useCase
     * @return Response
     * @throws PaginateDiscussionForbiddenException
     */
    public function __invoke(Request $request, Security $security, PaginateDiscussion $useCase): Response
    {
        $presenter = new PaginateDiscussionPresenter($this->twig, $this->getCurrentUser());
        $useCaseRequest = $this->requestFactory->create(
            $this->getCurrentUser(),
            [
                'page' => $request->query->get('page', 1)
            ]
        );
        $useCase->execute($useCaseRequest, $presenter);

        return $presenter->getResponse();
    }
}
