<?php

namespace App\UserInterface\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    /**
     * @throws Exception
     */
    protected function getCurrentUser(): ?\Messenger\Domain\Entity\UserInterface
    {
        $user = parent::getUser();
        if (null !== $user && !$user instanceof \Messenger\Domain\Entity\UserInterface) {
            throw new Exception(sprintf('Expected Messenger\Domain\Entity\UserInterface, got %s', get_class($user)));
        }
        return $user;
    }

}
