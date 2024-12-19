<?php

namespace App\Infrastructure\Adapter\Repository;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Query\QueryBuilder;
use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Entity\DiscussionMember;
use Messenger\Domain\Entity\Member;
use Messenger\Domain\Entity\Message;
use Messenger\Domain\Gateway\DiscussionGateway;
use Messenger\Domain\Gateway\MessageGateway;
use Symfony\Component\Uid\Uuid;

class MessageRepository implements MessageGateway
{
    private Connection $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @throws Exception
     */
    public function insert(Message $message): void
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->insert('messages')->values([
            'id' => ':id',
            'discussion_id' => ':discussion_id',
            'message' => ':message',
            'member_email' => ':member_email',
            'created_at' => ':created_at',])
            ->setParameter('id', $message->getId())
            ->setParameter('discussion_id', $message->getDiscussion()->getId()->toString())
            ->setParameter('message', $message->getMessage())
            ->setParameter('member_email', $message->getAuthor()->getEmail())
            ->setParameter('created_at', $message->getCreatedAt()->format(\DateTime::ATOM))
            ->executeStatement();
    }

    /**
     * @param array{"id"?:string ,"discussion.id"?: string} $filters
     */
    private function generateSelectQueryBuilder(array $filters): QueryBuilder
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->from('messages', 'm')
            ->join('m', 'members', 'me', 'm.member_email = me.email');
        if (isset($filters['discussion.id'])) {
            $queryBuilder
                ->where('m.discussion_id = :discussion_id')
                ->setParameter('discussion_id', $filters['discussion.id']);
        }
        if (isset($filters['id'])) {
            $queryBuilder
                ->where('m.id = :id')
                ->setParameter('id', $filters['id']);
        }
        return $queryBuilder;
    }

    /**
     * @throws Exception
     */
    public function countBy(array $filters): int
    {
        $qb = $this->generateSelectQueryBuilder($filters);
        $qb->select('count(m.id)');
        $response = $qb->fetchNumeric();
        return $response[0] ?? 0;
    }

    /**
     * @throws Exception
     */
    public function findBy(array $filters, array $options = []): array
    {
        $qb = $this->generateSelectQueryBuilder($filters);
        $qb->select('*');
        $rows = $qb->fetchAllAssociative();

        return array_map([self::class, 'parse'], $rows);
    }

    /**
     * @param array<string, mixed> $row
     * @return Message|null
     * @throws \DateMalformedStringException
     */
    static public function parse(array $row): ?Message
    {
        if (!$row) {
            return null;
        }
        if (!$row['id'] instanceof Uuid) {
            $row['id'] = new Uuid($row['id']);
        }
        $createdAt = new \DateTime($row['created_at']);
        return new Message($row['id'], $row['message'], MemberRepository::parse($row), null, $createdAt);
    }

    /**
     * @throws Exception
     */
    public function findOneById(string $messageId): ?Message
    {
        return $this->findBy(['id' => $messageId])[0] ?? null;
    }
}
