<?php

namespace App\UserInterface\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ProfileController extends BaseController
{

    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
