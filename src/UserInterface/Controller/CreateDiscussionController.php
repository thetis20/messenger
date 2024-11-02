<?php

namespace App\UserInterface\Controller;

use App\Domain\Messenger\Request\CreateDiscussionRequest;
use App\Domain\Messenger\UseCase\CreateDiscussion;
use App\Domain\Security\Gateway\UserGateway;
use App\UserInterface\Form\DiscussionType;
use App\UserInterface\Presenter\CreateDiscussionPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    private UserGateway $userGateway;
    private FormFactoryInterface $formFactory;
    private Environment $twig;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        UserGateway           $userGateway,
        FormFactoryInterface  $formFactory,
        Environment           $twig,
        UrlGeneratorInterface $urlGenerator)
    {
        $this->userGateway = $userGateway;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Request $request
     * @param CreateDiscussion $useCase
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request, CreateDiscussion $useCase): Response
    {
        $form = $this->formFactory->create(DiscussionType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $useCaseRequest = CreateDiscussionRequest::create(
                $form->getData()->getName(),
                $form->getData()->getUsernames(),
                $this->userGateway
            );
            $presenter = new CreateDiscussionPresenter($request);
            $useCase->execute($useCaseRequest, $presenter);
            return new RedirectResponse($this->urlGenerator->generate('index'));
        }
        return new Response($this->twig->render('create_discussion.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}
