<?php

namespace App\Tests\Integration;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Infrastructure\Adapter\Repository\MessageRepository;
use App\Infrastructure\Security\Dto\TokensBag;
use App\Infrastructure\Security\User;
use Messenger\Domain\Entity\Member;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class DeleteMessageDiscussionTest extends WebTestCase
{
    static public function data(): array
    {
        $members = [
            MemberRepository::parse([
                'email' => 'arnaud+delete-message@email.com',
                'userIdentifier' => 'arnaud+delete-message',
                'username' => 'Arnaud'
            ]),
            MemberRepository::parse([
                'email' => 'david+delete-message@email.com',
                'userIdentifier' => 'david+delete-message',
                'username' => 'David'
            ])];
        $discussions = [
            DiscussionRepository::parse([
                'id' => '1db779d5-76f4-49e5-8684-dbbdd7ce4951',
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
                ],
                'email' => $members[0]->getEmail(),
                'username' => $members[0]->getUsername(),
                'userIdentifier' => $members[0]->getUserIdentifier(),
            ])
        ];
        $messages = [MessageRepository::parse([
            'id' => 'c2c657cb-00f5-42da-b42f-2403b48c20f3',
            'created_at' => '2024-12-18 21:27:14.000000',
            'updated_at' => '2024-12-18 21:27:14.000000',
            'deleted' => false,
            'message' => 'Hello bro !',
            'discussion_id' => $discussions[0]->getId(),
            'email' => $members[0]->getEmail(),
            'userIdentifier' => $members[0]->getUserIdentifier(),
            'username' => $members[0]->getUsername(),
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
     * @return void
     */
    public function testSuccessful()
    {
        $data = self::data();
        $members = $data['members'];
        $messages = $data['messages'];
        $client = static::createClient();

        $messageRepository = $client->getContainer()->get(MessageRepository::class);
        $client->loginUser(new User(
            $members[0]->getUserIdentifier(),
            $members[0]->getEmail(),
            $members[0]->getUsername(),
            ['ROLE_USER']
        ), 'main', [
            TokensBag::class => new TokensBag('accessToken', 'refreshToken', time() + 3600),
        ]);
        $client->request(Request::METHOD_DELETE, '/api/messages/' . $messages[0]->getId()->toString());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $message = $messageRepository->find($messages[0]->getId()->toString());

        $this->assertTrue($message->isDeleted());
        $this->assertNull($message->getMessage());
    }
}
