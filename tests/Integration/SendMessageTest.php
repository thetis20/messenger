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
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class SendMessageTest extends WebTestCase
{
    /**
     * @return array{members: Member[], discussions: Discussion[], messages: Message[]}
     * @throws \DateMalformedStringException
     */
    static public function data(): array
    {
        $members = [MemberRepository::parse([
            'email' => 'arnaud+sendMessage@email.com',
            'userIdentifier' => 'arnaud+sendMessage',
            'username' => 'Arnaud'
        ]),
            MemberRepository::parse([
                'email' => 'david+sendMessage@email.com',
                'userIdentifier' => 'david+sendMessage',
                'username' => 'David'
            ]),
            MemberRepository::parse([
                'email' => 'mael+sendMessage@email.com',
                'userIdentifier' => 'mael+sendMessage',
                'username' => 'Maël'
            ])];
        $discussions = [DiscussionRepository::parse([
            'id' => '36ddc7b2-6cfe-4a39-b42a-59dd935ba8ca',
            'name' => 'Discussion 1 sendMessage',
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

    public function testSuccessful()
    {
        $data = self::data();
        $client = static::createClient();
        $client->loginUser(new User(
            $data['members'][0]->getUserIdentifier(),
            $data['members'][0]->getEmail(),
            $data['members'][0]->getUsername(),
            ['ROLE_USER']
        ), 'main', [
            TokensBag::class => new TokensBag('accessToken', 'refreshToken', time() + 3600),
        ]);
        $crawler = $client->request(Request::METHOD_GET, '/discussions/36ddc7b2-6cfe-4a39-b42a-59dd935ba8ca');

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form')->form();
        $client->submit($form, [
            'message[message]' => 'message 1'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testFailed()
    {

        $data = self::data();
        $client = static::createClient();
        $client->loginUser(new User(
            $data['members'][0]->getUserIdentifier(),
            $data['members'][0]->getEmail(),
            $data['members'][0]->getUsername(),
            ['ROLE_USER']
        ), 'main', [
            TokensBag::class => new TokensBag('accessToken', 'refreshToken', time() + 3600),
        ]);
        $crawler = $client->request(Request::METHOD_GET, '/discussions/36ddc7b2-6cfe-4a39-b42a-59dd935ba8ca');

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form')->form();
        $client->submit($form, [
            'message[message]' => ''
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('html', 'This value should not be blank.');
    }
}
