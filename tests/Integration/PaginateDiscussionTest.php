<?php

namespace App\Tests\Integration;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Infrastructure\Security\Dto\TokensBag;
use App\Infrastructure\Security\User;
use Messenger\Domain\Entity\Member;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaginateDiscussionTest extends WebTestCase
{


    static public function data(): array
    {
        $members = [MemberRepository::parse([
            'email' => 'arnaud@email.com',
            'userIdentifier' => 'arnaud',
            'username' => 'Arnaud'
        ]),
            MemberRepository::parse([
                'email' => 'david@email.com',
                'userIdentifier' => 'david',
                'username' => 'David'
            ]),
            MemberRepository::parse([
                'email' => 'mael+paginate-discussion@email.com',
                'userIdentifier' => 'mael+paginate-discussion',
                'username' => 'Maël'
            ]),
            MemberRepository::parse([
                'email' => 'mahamadou+paginate-discussion@email.com',
                'userIdentifier' => 'mahamadou+paginate-discussion',
                'username' => 'Mahamadou'
            ]),
            MemberRepository::parse([
                'email' => 'natalie+paginate-discussion@email.com',
                'userIdentifier' => 'natalie+paginate-discussion',
                'username' => 'Natalie'
            ])];
        $discussions = [DiscussionRepository::parse([
            'id' => '4251fc73-d34e-41fb-93e6-53429fa03461',
            'name' => 'Discussion 1',
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
            ]
        ]),
            DiscussionRepository::parse([
                'id' => 'dc14b741-1c86-480c-973c-797e1701cbe0',
                'name' => 'Discussion 2',
                'discussionMembers' => [
                    [
                        'member' => $members[0],
                        'seen' => false,
                    ],
                    [
                        'member' => $members[2],
                        'seen' => false,
                    ],
                    [
                        'member' => $members[3],
                        'seen' => false,
                    ]
                ]
            ]),
            DiscussionRepository::parse([
                'id' => '0ff56bf5-6904-418b-8951-4f33ff31baff',
                'name' => 'Discussion 3',
                'discussionMembers' => [
                    [
                        'member' => $members[0],
                        'seen' => true,
                    ],
                    [
                        'member' => $members[3],
                        'seen' => false,
                    ],
                    [
                        'member' => $members[4],
                        'seen' => false,
                    ]
                ]
            ])];
        return [
            'members' => $members,
            'discussions' => $discussions
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
    public function testSuccessful(Member $member, array $discussions, int $count, int $unseenCount)
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
        $client->request(Request::METHOD_GET, '/discussions/list');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        foreach ($discussions as $discussion) {
            $this->assertSelectorTextContains("#discussion_list_item_".$discussion->getId()->toString(), $discussion->getName());
        }

        $this->assertSelectorCount($count, '[data-type="discussion_list_item"]');
        $this->assertSelectorCount($count - $unseenCount, '[data-type="discussion_list_item"][data-seen="true"]');
        $this->assertSelectorCount($unseenCount, '[data-type="discussion_list_item"][data-seen="false"]');
    }

    public function provideData(): \Generator
    {
        $data = self::data();
        yield [$data['members'][0], $data['discussions'], 3, 1];
        yield [$data['members'][1], [$data['discussions'][0]], 1, 1];
        yield [$data['members'][2], [$data['discussions'][0]], 2, 2];
        yield [$data['members'][3], [$data['discussions'][1], $data['discussions'][2]], 2, 2];
    }
}
