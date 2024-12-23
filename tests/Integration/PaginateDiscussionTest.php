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
            ],
            'email' => $members[0]->getEmail(),
            'username' => $members[0]->getUsername(),
            'userIdentifier' => $members[0]->getUserIdentifier(),
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
                ],
                'email' => $members[0]->getEmail(),
                'username' => $members[0]->getUsername(),
                'userIdentifier' => $members[0]->getUserIdentifier(),
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
                ],
                'email' => $members[0]->getEmail(),
                'username' => $members[0]->getUsername(),
                'userIdentifier' => $members[0]->getUserIdentifier(),
            ]),
            DiscussionRepository::parse([
                'id' => '19a5b0da-0afc-41b2-8bed-fbe84c3b4450',
                'name' => 'Discussion 4',
                'discussionMembers' => [
                    [
                        'member' => $members[0],
                        'seen' => true,
                    ],
                    [
                        'member' => $members[1],
                        'seen' => false,
                    ]
                ],
                'email' => $members[0]->getEmail(),
                'username' => $members[0]->getUsername(),
                'userIdentifier' => $members[0]->getUserIdentifier(),
            ]),
            DiscussionRepository::parse([
                'id' => 'e0dd4695-68e3-43a1-b415-5d12c30003d1',
                'name' => 'Discussion 5',
                'discussionMembers' => [
                    [
                        'member' => $members[0],
                        'seen' => true,
                    ],
                    [
                        'member' => $members[2],
                        'seen' => false,
                    ]
                ],
                'email' => $members[0]->getEmail(),
                'username' => $members[0]->getUsername(),
                'userIdentifier' => $members[0]->getUserIdentifier(),
            ]),
            DiscussionRepository::parse([
                'id' => '6f0d2c5b-4135-4ec0-809f-ff75def4f78e',
                'name' => 'Discussion 6',
                'discussionMembers' => [
                    [
                        'member' => $members[0],
                        'seen' => true,
                    ],
                    [
                        'member' => $members[3],
                        'seen' => false,
                    ]
                ],
                'email' => $members[0]->getEmail(),
                'username' => $members[0]->getUsername(),
                'userIdentifier' => $members[0]->getUserIdentifier(),
            ]),
            DiscussionRepository::parse([
                'id' => '7324b546-a5b3-46b6-a5ab-832e9b1dc630',
                'name' => 'Discussion 7',
                'discussionMembers' => [
                    [
                        'member' => $members[0],
                        'seen' => true,
                    ],
                    [
                        'member' => $members[4],
                        'seen' => false,
                    ]
                ],
                'email' => $members[0]->getEmail(),
                'username' => $members[0]->getUsername(),
                'userIdentifier' => $members[0]->getUserIdentifier(),
            ]),
            DiscussionRepository::parse([
                'id' => '9468846c-4cce-4567-aab5-d961e2684e3a',
                'name' => 'Discussion 8',
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
                        'member' => $members[3],
                        'seen' => false,
                    ]
                ],
                'email' => $members[0]->getEmail(),
                'username' => $members[0]->getUsername(),
                'userIdentifier' => $members[0]->getUserIdentifier(),
            ]),
            DiscussionRepository::parse([
                'id' => 'b6b2de3a-f6ef-4679-8892-289f2615eedb',
                'name' => 'Discussion 9',
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
                        'member' => $members[4],
                        'seen' => false,
                    ]
                ],
                'email' => $members[0]->getEmail(),
                'username' => $members[0]->getUsername(),
                'userIdentifier' => $members[0]->getUserIdentifier(),
            ]),
            DiscussionRepository::parse([
                'id' => 'e0b355d8-b85c-4c6b-98bf-ae531064e713',
                'name' => 'Discussion 10',
                'discussionMembers' => [
                    [
                        'member' => $members[0],
                        'seen' => true,
                    ],
                    [
                        'member' => $members[2],
                        'seen' => false,
                    ],
                    [
                        'member' => $members[4],
                        'seen' => false,
                    ]
                ],
                'email' => $members[0]->getEmail(),
                'username' => $members[0]->getUsername(),
                'userIdentifier' => $members[0]->getUserIdentifier(),
            ]),
            DiscussionRepository::parse([
                'id' => '9a05df87-dbd1-44d1-9ffe-c244080075eb',
                'name' => 'Discussion 10',
                'discussionMembers' => [
                    [
                        'member' => $members[0],
                        'seen' => true,
                    ],
                    [
                        'member' => $members[2],
                        'seen' => false,
                    ],
                    [
                        'member' => $members[3],
                        'seen' => false,
                    ],
                    [
                        'member' => $members[4],
                        'seen' => false,
                    ]
                ],
                'email' => $members[0]->getEmail(),
                'username' => $members[0]->getUsername(),
                'userIdentifier' => $members[0]->getUserIdentifier(),
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
    public function testSuccessful(Member $member, array $discussions, int $count, int $unseenCount, int $page = 1)
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
        $client->request(Request::METHOD_GET, '/discussions/list?page=' . $page);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        foreach ($discussions as $discussion) {
            $this->assertSelectorTextContains("#discussion_list_item_" . $discussion->getId()->toString(), $discussion->getName());
        }

        $this->assertSelectorCount($count, '[data-type="discussion_list_item"]');
        $this->assertSelectorCount($count - $unseenCount, '[data-type="discussion_list_item"][data-seen="true"]');
        $this->assertSelectorCount($unseenCount, '[data-type="discussion_list_item"][data-seen="false"]');
    }

    public function provideData(): \Generator
    {
        $data = self::data();
        yield [$data['members'][0], [], 10, 0];
        yield [$data['members'][0], [], 1, 0, 2];
        yield [$data['members'][1], [$data['discussions'][0]], 4, 4];
        yield [$data['members'][2], [$data['discussions'][0]], 5, 5];
        yield [$data['members'][3], [$data['discussions'][1], $data['discussions'][2]], 5, 5];
    }
}
