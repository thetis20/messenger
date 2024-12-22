<?php

namespace DataFixtures;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Tests\Integration\MarkAsSeenDiscussionTest;
use App\Tests\Integration\PaginateDiscussionTest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class MarkAsSeenDiscussionFixtures extends Fixture
{

    public function __construct(
        private readonly DiscussionRepository $discussionRepository,
        private readonly MemberRepository     $memberRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $data = MarkAsSeenDiscussionTest::data();

        foreach ($data['members'] as $member) {
            $this->memberRepository->insert($member);
        }
        foreach ($data['discussions'] as $discussion) {
            $this->discussionRepository->insert($discussion);
        }
    }
}