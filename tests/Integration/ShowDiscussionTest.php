<?php

namespace App\Tests\Integration;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Infrastructure\Adapter\Repository\MessageRepository;
use App\Infrastructure\Security\Dto\TokensBag;
use App\Infrastructure\Security\User;
use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Entity\Member;
use Messenger\Domain\Entity\Message;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class ShowDiscussionTest extends WebTestCase
{
    /**
     * @return array{members: Member[], discussions: Discussion[], messages: Message[]}
     * @throws \DateMalformedStringException
     */
    static public function data(): array
    {
        $members = [MemberRepository::parse([
            'email' => 'arnaud+showDiscussion@email.com',
            'userIdentifier' => 'arnaud+showDiscussion',
            'username' => 'Arnaud'
        ]),
            MemberRepository::parse([
                'email' => 'david+showDiscussion@email.com',
                'userIdentifier' => 'david+showDiscussion',
                'username' => 'David'
            ]),
            MemberRepository::parse([
                'email' => 'mael+showDiscussion@email.com',
                'userIdentifier' => 'mael+showDiscussion',
                'username' => 'Maël'
            ])];
        $discussions = [DiscussionRepository::parse([
            'id' => 'be5fbc83-c6f4-4665-8007-f3782b3552f6',
            'name' => 'Discussion 1 showDiscussion',
            'discussionMembers' => [
                [
                    'member' => $members[0],
                    'seen' => true,
                ],
                [
                    'member' => $members[1],
                    'seen' => false,
                ],
                [
                    'member' => $members[2],
                    'seen' => false,
                ]
            ],
            'email' => $members[0]->getEmail(),
            'username' => $members[0]->getUsername(),
            'userIdentifier' => $members[0]->getUserIdentifier(),
        ])];
        $messages = [MessageRepository::parse([
            'id' => Uuid::v4(),
            'created_at' => '2024-12-18 21:27:14.000000',
            'updated_at' => '2024-12-18 21:27:14.000000',
            'deleted' => false,
            'message' => 'Hello bro !',
            'discussion_id' => $discussions[0]->getId(),
            'email' => $members[0]->getEmail(),
            'userIdentifier' => $members[0]->getUserIdentifier(),
            'username' => $members[0]->getUsername(),
        ]),
            MessageRepository::parse([
                'id' => Uuid::v4(),
                'created_at' => '2024-12-18 21:27:14.000000',
                'updated_at' => '2024-12-18 21:27:14.000000',
                'deleted' => false,
                'message' => 'What\'s up?',
                'discussion_id' => $discussions[0]->getId(),
                'email' => $members[1]->getEmail(),
                'userIdentifier' => $members[1]->getUserIdentifier(),
                'username' => $members[1]->getUsername(),
            ]),
            MessageRepository::parse([
                'id' => Uuid::v4(),
                'created_at' => '2024-12-18 21:27:14.000000',
                'updated_at' => '2024-12-18 21:27:14.000000',
                'deleted' => false,
                'message' => 'Fine and you ?',
                'discussion_id' => $discussions[0]->getId(),
                'email' => $members[2]->getEmail(),
                'userIdentifier' => $members[2]->getUserIdentifier(),
                'username' => $members[2]->getUsername(),
            ])];
        return [
            'members' => $members,
            'discussions' => $discussions,
            'messages' => $messages,
        ];
    }

    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        return parent::createClient(array_merge($options, ['environment' => 'test']), $server);
    }

    /**
     * @dataProvider provideData
     * @return void
     */
    public function testSuccessful(Member $member, Discussion $discussion, int $count)
    {
        $client = static::createClient();
        $client->loginUser(new User(
            $member->getUserIdentifier(),
            $member->getEmail(),
            $member->getUsername(),
            ['ROLE_USER']
        ), 'main', [
            TokensBag::class => new TokensBag('accessToken', 'refreshToken', time() + 3600),
        ]);
        $client->request(Request::METHOD_GET, '/discussions/' . $discussion->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorCount($count, '[data-type="discussion_show_message"]');
    }

    public function provideData(): \Generator
    {
        $data = self::data();
        yield [$data['members'][0], $data['discussions'][0], 3];
        yield [$data['members'][1], $data['discussions'][0], 3];
    }
}
