<?php

namespace App\Infrastructure\Adapter\Repository;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Messenger\Domain\Entity\Discussion;
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
            'name' => ':name',
            'author_email' => ':author_email'])
            ->setParameter('id', $discussion->getId())
            ->setParameter('name', $discussion->getName())
            ->setParameter('author_email', $discussion->getAuthor()->getEmail())
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
            ->where('id = :id')
            ->setParameter('name', $discussion->getName())
            ->setParameter('id', $discussion->getId())
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

    /**
     * @param array{"id"?:string, "discussionMembers.member.email"?: string} $filters
     */
    private function generateSelectQueryBuilder(array $filters): QueryBuilder
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->from('discussions', 'd')
            ->join('d', 'discussion_members', 'dm', 'd.id = dm.discussion_id');
        if (isset($filters['discussionMembers.member.email'])) {
            $queryBuilder
                ->where('dm.member_email = :member_email')
                ->setParameter('member_email', $filters['discussionMembers.member.email']);
        }
        if (isset($filters['id'])) {
            $queryBuilder
                ->where('d.id = :id')
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
        $qb->select('count(d.id)');
        $response = $qb->fetchNumeric();
        return $response[0] ?? 0;
    }

    /**
     * @throws Exception
     */
    public function findBy(array $filters, array $options = []): array
    {
        $qb = $this->generateSelectQueryBuilder($filters)
            ->join('d', 'members', 'a', 'a.email = d.author_email')
            ->groupBy('d.id', 'd.name', 'a.email', 'a.username', 'a.userIdentifier')
            ->select('d.id', 'd.name', 'a.email', 'a.username', 'a.userIdentifier');

        if (isset($options['limit'])) {
            $qb->setMaxResults($options['limit']);
        }

        if (isset($options['offset'])) {
            $qb->setFirstResult($options['offset']);
        }

        $rows = $qb->fetchAllAssociative();
        $array = [];

        $queryBuilder = $this->conn->createQueryBuilder();
        $discussionMemberRows = $queryBuilder->from('discussion_members', 'dm')
            ->join('dm', 'members', 'm', 'm.email = dm.member_email')
            ->where('dm.discussion_id IN (:discussions)')
            ->setParameter('discussions', array_map(function (array $row) {
                return $row['id'];
            }, $rows), ArrayParameterType::STRING)
            ->select('discussion_id', 'seen', 'm.email as email', 'm.username as username', 'm.userIdentifier as userIdentifier')
            ->fetchAllAssociative();
        foreach ($rows as $row) {
            $array[$row['id']] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'email' => $row['email'],
                'username' => $row['username'],
                'userIdentifier' => $row['useridentifier'],
                'discussionMembers' => []
            ];
        }

        foreach ($discussionMemberRows as $row) {
            $array[$row['discussion_id']]['discussionMembers'][] = $row;
        }

        return array_map([self::class, 'parse'], array_values($array));
    }

    /**
     * @param array{
     *     id: string|Uuid,
     *     name: string,
     *     discussionMembers: array<array>,
     *     email: string,
     *     useridentifier: ?string,
     *     userIdentifier: ?string,
     *     username: string,
     * }|null $row
     * @return Discussion|null
     */
    static public function parse(?array $row): ?Discussion
    {
        if (!$row) {
            return null;
        }
        if (!$row['id'] instanceof Uuid) {
            $row['id'] = new Uuid($row['id']);
        }
        $discussion = new Discussion(MemberRepository::parse($row), $row['id'], $row['name']);
        foreach ($row['discussionMembers'] as $item) {
            if (!isset($item['member'])) {
                $item['member'] = MemberRepository::parse($item);
            }
            $discussion->addMember($item['member'], $item['seen']);
        }
        return $discussion;
    }

    public function find(string $id): ?Discussion
    {
        return $this->findBy(['id' => $id])[0] ?? null;
    }
}
