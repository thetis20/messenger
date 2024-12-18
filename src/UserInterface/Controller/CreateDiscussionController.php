<?php

namespace App\UserInterface\Controller;

use Messenger\Domain\Exception\CreateDiscussionForbiddenException;
use Messenger\Domain\Request\CreateDiscussionRequest;
use Messenger\Domain\UseCase\CreateDiscussion;
use App\UserInterface\Form\DiscussionType;
use App\UserInterface\Presenter\CreateDiscussionPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateDiscussionController extends AbstractController
{

    public function __construct(
        private readonly FormFactoryInterface  $formFactory,
        private readonly Environment           $twig,
        private readonly UrlGeneratorInterface $urlGenerator, )
    {
    }

    /**
     * @param Request $request
     * @param Security $security
     * @param CreateDiscussion $useCase
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws CreateDiscussionForbiddenException
     */
    public function __invoke(Request $request, Security $security, CreateDiscussion $useCase): Response
    {
        $form = $this->formFactory->create(DiscussionType::class)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $useCaseRequest = CreateDiscussionRequest::create(
                $form->getData()->getName(),
                $form->getData()->getEmails(),
                $this->getUser()
            );
            $presenter = new CreateDiscussionPresenter($this->urlGenerator, $this->getUser());
            $useCase->execute($useCaseRequest, $presenter);
            return $presenter->getResponse();
        }
        return new Response($this->twig->render('discussions_create.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}
