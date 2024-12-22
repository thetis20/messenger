<?php

namespace DataFixtures;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Infrastructure\Adapter\Repository\MessageRepository;
use App\Tests\Infrastructure\MemberRepositoryTest;
use App\Tests\Integration\PaginateDiscussionTest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class MemberRepositoryFixtures extends Fixture
{

    public function __construct(
        private readonly MemberRepository     $memberRepository,
        private readonly DiscussionRepository $discussionRepository,
        private readonly MessageRepository    $messageRepository,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $data = MemberRepositoryTest::data();

        foreach ($data['members'] as $member) {
            $this->memberRepository->insert($member);
        }
    }
}