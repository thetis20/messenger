<?php

namespace DataFixtures;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Infrastructure\Adapter\Repository\MessageRepository;
use App\Tests\Infrastructure\MessageRepositoryTest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class MessageRepositoryFixtures extends Fixture
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
        $data = MessageRepositoryTest::data();

        foreach ($data['members'] as $member) {
            $this->memberRepository->insert($member);
        }
        foreach ($data['discussions'] as $discussion) {
            $this->discussionRepository->insert($discussion);
        }
        foreach ($data['messages'] as $message) {
            $this->messageRepository->insert($message);
        }
    }
}
