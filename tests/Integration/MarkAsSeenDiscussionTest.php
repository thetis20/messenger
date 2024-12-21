<?php

namespace App\Tests\Integration;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Infrastructure\Security\Dto\TokensBag;
use App\Infrastructure\Security\User;
use Messenger\Domain\Entity\Member;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MarkAsSeenDiscussionTest extends WebTestCase
{
    static public function data(): array
    {
        $members = [
            MemberRepository::parse([
                'email' => 'arnaud+mark-as-seen@email.com',
                'userIdentifier' => 'arnaud+mark-as-seen',
                'username' => 'Arnaud'
            ]),
            MemberRepository::parse([
                'email' => 'david+mark-as-seen@email.com',
                'userIdentifier' => 'david+mark-as-seen',
                'username' => 'David'
            ])];
        $discussions = [
            DiscussionRepository::parse([
                'id' => '308c25bf-77f8-4956-a5d7-2661873beaa1',
                'name' => 'Discussion 1',
                'discussionMembers' => [
                    [
                        'member' => $members[0],
                        'seen' => false,
                    ],
                    [
                        'member' => $members[1],
                        'seen' => false,
                    ]
                ]
            ])
        ];
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
     * @return void
     */
    public function testSuccessful()
    {
        $data = self::data();
        $members = $data['members'];
        $discussions = $data['discussions'];
        $client = static::createClient();

        $discussionRepository = $client->getContainer()->get(DiscussionRepository::class);
        $client->loginUser(new User(
            $members[0]->getUserIdentifier(),
            $members[0]->getEmail(),
            $members[0]->getUsername(),
            ['ROLE_USER']
        ), 'main', [
            TokensBag::class => new TokensBag('accessToken', 'refreshToken', time() + 3600),
        ]);
        $client->request(Request::METHOD_POST, '/api/discussions/' . $discussions[0]->getId()->toString() . '/markAsSeen');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $discussion = $discussionRepository->find($discussions[0]->getId());
        $discussionMember = $discussion->findDiscussionMemberByEmail($members[0]->getEmail());

        $this->assertEquals(true, $discussionMember->isSeen());
    }
}
