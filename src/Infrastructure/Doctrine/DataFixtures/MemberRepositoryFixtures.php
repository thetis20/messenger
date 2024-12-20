<?php

namespace DataFixtures;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Tests\Infrastructure\MemberRepositoryTest;
use App\Tests\Integration\PaginateDiscussionTest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class MemberRepositoryFixtures extends Fixture
{

    public function __construct(
        private readonly DiscussionRepository $discussionRepository,
        private readonly MemberRepository     $memberRepository)
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
