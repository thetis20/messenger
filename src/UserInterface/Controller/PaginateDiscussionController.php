<?php

namespace App\UserInterface\Controller;

use Messenger\Domain\Exception\PaginateDiscussionForbiddenException;
use Messenger\Domain\Request\PaginateDiscussionRequest;
use App\UserInterface\Presenter\PaginateDiscussionPresenter;
use Messenger\Domain\UseCase\PaginateDiscussion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class PaginateDiscussionController extends AbstractController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
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
        $presenter = new PaginateDiscussionPresenter($this->twig, $this->getUser());
        $useCaseRequest = PaginateDiscussionRequest::create(
            $this->getUser(),
            [
                'page' => $request->query->get('page', 1)
            ]
        );
        $useCase->execute($useCaseRequest, $presenter);

        return $presenter->getResponse();
    }
}
