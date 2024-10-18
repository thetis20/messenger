<?php

namespace App\UserInterface\Controller;

use App\Infrastructure\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SignUpController extends AbstractController
{
    #[Route('/sign-up', name: 'sign-up')]
    public function signUp(Request $request, EntityManagerInterface $entityManager): Response
    {
        // creates a task object and initializes some data for this example

        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('email', TextType::class, [
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('firstName', TextType::class, [
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('lastName', TextType::class, [
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('usualName', TextType::class, [
                'row_attr' => ['class' => 'form-group'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, ['label' => 'Sing-up',
                'attr' => ['class' => 'btn btn-primary']])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();

            $entityManager->persist($user);

            $entityManager->flush();

            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
        }

        // ...
        /*$user = new User();
        $user->setEmail('arnaud@factoryz.fr');
        $user->setFirstName('Arnaud');
        $user->setLastName('Picard');

        $entityManager->persist($user);

        $entityManager->flush();*/

        return $this->render('sign-up.html.twig', [
            'form' => $form,
        ]);
    }
}
