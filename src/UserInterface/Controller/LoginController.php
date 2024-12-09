<?php

namespace App\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class LoginController extends AbstractController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()){
            return new RedirectResponse($this->generateUrl('profile'));
        }
        return new Response($this->twig->render('login.html.twig'));
    }
}
