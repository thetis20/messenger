<?php

namespace App\Infrastructure\Adapter\Repository;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use App\Infrastructure\Doctrine\Entity\DoctrineUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

class UserRepository extends ServiceEntityRepository implements UserGateway
{
    public static function parseUser(?DoctrineUser $doctrineUser): ?User
    {
        if (!$doctrineUser) {
            return null;
        }
        return new User(
            $doctrineUser->getId(),
            $doctrineUser->getEmail(),
            $doctrineUser->getUsername(),
            $doctrineUser->getPassword()
        );
    }

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
        $doctrineUser->setId($user->getId());
        $doctrineUser->setEmail($user->getEmail());
        $doctrineUser->setUsername($user->getUsername());
        $doctrineUser->setPassword($user->getPassword());
        $this->getEntityManager()->persist($doctrineUser);
        $this->getEntityManager()->flush($doctrineUser);
    }

    public function findOneByUsername(string $username): ?User
    {
        return self::parseUser($this->findOneBy(['username' => $username]));
    }

    public function findOneById(Uuid|string $id): ?User
    {
        return self::parseUser($this->findOneBy(['id' => $id]));
    }
}
