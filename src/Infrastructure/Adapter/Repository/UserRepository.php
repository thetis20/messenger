<?php

namespace App\Infrastructure\Adapter\Repository;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use App\Infrastructure\Doctrine\Entity\DoctrineUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctrineUser::class);
    }

    public function emailAlreadyExists(?string $email): bool
    {
        return $this->count(['email' => $email]) > 0;
    }

    public function usernameAlreadyExists(?string $username): bool
    {
        return $this->count(['username' => $username]) > 0;
    }

    public function register(User $user): void
    {
        $doctrineUser = new DoctrineUser();
        $doctrineUser->setEmail($user->getEmail());
        $doctrineUser->setUsername($user->getUsername());
        $doctrineUser->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
        $doctrineUser->setId($user->getId());
        $this->getEntityManager()->persist($doctrineUser);
        $this->getEntityManager()->flush($doctrineUser);
    }
}
