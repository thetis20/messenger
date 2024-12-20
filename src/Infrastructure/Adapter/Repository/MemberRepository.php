<?php

namespace App\Infrastructure\Adapter\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Messenger\Domain\Entity\Member;
use Messenger\Domain\Gateway\MemberGateway;

class MemberRepository implements MemberGateway
{
    private Connection $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param array{
     *     email: string,
     *     useridentifier?: string,
     *     userIdentifier?: string,
     *     username: string
     * }|null $row
     * @return Member|null
     */
    static function parse(?array $row): ?Member
    {
        if (!$row) {
            return null;
        }
        return new Member(
            $row['email'],
            $row['userIdentifier'] ?? $row['useridentifier'],
            $row['username']
        );

    }

    /**
     * @throws Exception
     */
    public function insert(Member $member): void
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->insert('members')->values([
            'email' => ':email',
            'useridentifier' => ':useridentifier',
            'username' => ':username',])
            ->setParameter('email', $member->getEmail())
            ->setParameter('useridentifier', $member->getUserIdentifier())
            ->setParameter('username', $member->getUsername());
        $queryBuilder->executeStatement();
    }

    /**
     * @throws Exception
     */
    public function update(Member $member): void
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->update('members')
            ->set('useridentifier', ':useridentifier')
            ->set('username', ':username')
            ->where('email = :email')
            ->setParameter('useridentifier', $member->getUserIdentifier())
            ->setParameter('username', $member->getUsername())
            ->setParameter('email', $member->getEmail());
        $queryBuilder->executeStatement();
    }

    /**
     * @throws Exception
     */
    public function findOneByEmail(string $email): ?Member
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $result = $queryBuilder
            ->select('*')
            ->from('members')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery()
            ->fetchAllAssociative();
        $m = self::parse($result[0] ?? null);
        return $m;
    }

    /**
     * @throws Exception
     */
    public function save(Member $member): void
    {
        $isExists = !!$this->findOneByEmail($member->getEmail());
        if ($isExists) {
            $this->update($member);
        } else {
            $this->insert($member);
        }
    }
}
