<?php

namespace App\Infrastructure\Adapter\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Gateway\DiscussionGateway;

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
}
