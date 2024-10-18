<?php

namespace App\UserInterface\Controller;

use App\Infrastructure\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/users/{id}', name: 'users_show', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {

        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id ' . $id
            );
        }

        return new Response('user: ' . $user->getUsualName());
    }
}
