<?php

namespace App\Infrastructure\Doctrine\DataFixtures;

use App\Infrastructure\Doctrine\Entity\DoctrineUser;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Uid\Uuid;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new DoctrineUser();
        $user->setId(Uuid::v4());
        $user->setUsername("username");
        $user->setEmail("username@email.com");
        $user->setPassword(password_hash("password", PASSWORD_ARGON2I));
        $manager->persist($user);
        $manager->flush();
    }
}
