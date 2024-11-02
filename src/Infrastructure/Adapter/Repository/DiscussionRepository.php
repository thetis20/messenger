<?php

namespace App\Infrastructure\Adapter\Repository;

use App\Domain\Messenger\Entity\Discussion;
use App\Domain\Messenger\Gateway\DiscussionGateway;
use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use App\Infrastructure\Doctrine\Entity\DoctrineDiscussion;
use App\Infrastructure\Doctrine\Entity\DoctrineUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

class DiscussionRepository extends ServiceEntityRepository implements DiscussionGateway
{
    public static function parser(?DoctrineUser $doctrineUser): ?User
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

    public function insert(Discussion $discussion): void
    {
        $doctrineDiscussion = new DoctrineDiscussion();
        $doctrineDiscussion->setId($discussion->getId());
        $doctrineDiscussion->setName($discussion->getName());
        foreach ($discussion->getMembers() as $member) {
            $doctrineUser = $this->getEntityManager()->getRepository(DoctrineUser::class)->find($member->getId());
            $doctrineDiscussion->addMember($doctrineUser);
        }
        $this->getEntityManager()->persist($doctrineDiscussion);
        $this->getEntityManager()->flush();
    }
}
