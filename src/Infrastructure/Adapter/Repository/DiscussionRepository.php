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
use Messenger\Domain\Gateway\DiscussionGateway;
use Symfony\Component\Uid\Uuid;

class DiscussionRepository implements DiscussionGateway
{
    private Connection $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @throws Exception
     */
    public function insert(Discussion $discussion): void
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->insert('discussions')->values([
            'id' => ':id',
            'name' => ':name'])
            ->setParameter('id', $discussion->getId())
            ->setParameter('name', $discussion->getName())
            ->executeStatement();
        $this->_updateDiscussionMembers($discussion);
    }

    /**
     * @throws Exception
     */
    public function update(Discussion $discussion): void
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->update('discussions')
            ->set('name', ':name')
            ->setParameter('name', $discussion->getName())
            ->executeStatement();
        $this->_updateDiscussionMembers($discussion);
    }

    /**
     * @throws Exception
     */
    private function _updateDiscussionMembers(Discussion $discussion): void
    {

        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->delete('discussion_members')
            ->where('discussion_id = :discussion_id')
            ->setParameter('discussion_id', $discussion->getId())
            ->executeStatement();
        foreach ($discussion->getDiscussionMembers() as $discussionMember) {
            $queryBuilder = $this->conn->createQueryBuilder();
            $queryBuilder->insert('discussion_members')->values([
                'discussion_id' => ':discussion_id',
                'member_email' => ':member_email',
                'seen' => ':seen',
            ])
                ->setParameter('discussion_id', $discussion->getId())
                ->setParameter('member_email', $discussionMember->getMember()->getEmail())
                ->setParameter('seen', $discussionMember->isSeen() ? 1 : 0)
                ->executeStatement();
        }
    }

    private function generateSelectQueryBuilder(array $filters): QueryBuilder
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->from('discussions', 'd')
            ->join('d', 'discussion_members', 'dm', 'd.id = dm.discussion_id')
            ->groupBy('d.id', 'd.name');
        if (isset($filters['discussionMembers.member.email'])) {
            $queryBuilder
                ->where('dm.member_email = :member_email')
                ->setParameter('member_email', $filters['discussionMembers.member.email']);
        }
        return $queryBuilder;
    }

    /**
     * @throws Exception
     */
    public function countBy(array $filters): int
    {
        $qb = $this->generateSelectQueryBuilder($filters);
        $qb->select('count(d.id)');
        $response = $qb->fetchNumeric();
        return $response[0] ?? 0;
    }

    /**
     * @throws Exception
     */
    public function findBy(array $filters, array $options): array
    {
        $qb = $this->generateSelectQueryBuilder($filters);
        $qb->select('d.id', 'd.name');
        $rows = $qb->fetchAllAssociative();
        return $this->parse($rows);
    }

    /**
     * @param array<string, mixed> $rows
     * @param array<string, mixed> $rows
     * @return Discussion[]
     * @throws Exception
     */
    public function parse(array $rows): array
    {
        $results = [];
        $queryBuilder = $this->conn->createQueryBuilder();
        $members = $queryBuilder->from('discussion_members', 'dm')
            ->join('dm', 'members', 'm', 'm.email = dm.member_email')
            ->where('dm.discussion_id IN (:discussions)')
            ->setParameter('discussions', array_map(function (array $row) {
                return $row['id'];
            }, $rows), ArrayParameterType::STRING)
            ->select('*')
            ->fetchAllAssociative();

        foreach ($rows as $row) {
            $results[$row['id']] = new Discussion(new Uuid($row['id']), $row['name']);
        }

        foreach ($members as $row) {
            $member = MemberRepository::parse($row);
            $results[$row['discussion_id']]->addMember($member, $row['seen']);
        }
        return array_values($results);
    }
}
