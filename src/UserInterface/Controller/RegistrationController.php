<?php

namespace App\UserInterface\Controller;

use App\Domain\Security\Request\RegistrationRequest;
use App\Domain\Security\UseCase\Registration;
use App\Infrastructure\Entity\User;
use App\UserInterface\Form\RegistrationType;
use App\UserInterface\Presenter\RegistrationPresenter;
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

class RegistrationController extends AbstractController
{
    private FormFactoryInterface $formFactory;
    private Environment $twig;
    //private FlashBagInterface $flashBag;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(FormFactoryInterface $formFactory, Environment $twig, UrlGeneratorInterface $urlGenerator)
    {
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Request $request
     * @param Registration $registration
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request, Registration $registration): Response
    {
        $form = $this->formFactory->create(RegistrationType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $request = RegistrationRequest::create(
                $form->getData()->getEmail(),
                $form->getData()->getUsername(),
                $form->getData()->getPlainPassword(),
            );
            $presenter = new RegistrationPresenter();
            $registration->execute($request, $presenter);
            $this->addFlash('success', 'Welcome to messenger !');
            return new RedirectResponse($this->urlGenerator->generate('index'));
        }
        return new Response($this->twig->render('registration.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}
