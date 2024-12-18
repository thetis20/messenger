<?php

namespace DataFixtures;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Tests\Integration\PaginateDiscussionTest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PaginateDiscussionFixtures extends Fixture
{
    private MemberRepository $memberRepository;
    private DiscussionRepository $discussionRepository;

    public function __construct(
        DiscussionRepository $discussionRepository,
        MemberRepository     $memberRepository)
    {
        $this->discussionRepository = $discussionRepository;
        $this->memberRepository = $memberRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $data = PaginateDiscussionTest::data();

        foreach ($data['members'] as $member) {
            $this->memberRepository->insert($member);
        }
        foreach ($data['discussions'] as $discussion) {
            $this->discussionRepository->insert($discussion);
        }
    }
}
