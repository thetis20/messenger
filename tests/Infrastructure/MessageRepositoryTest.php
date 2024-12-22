<?php

namespace App\Tests\Infrastructure;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Infrastructure\Adapter\Repository\MessageRepository;
use Doctrine\DBAL\Exception;
use Messenger\Domain\Gateway\MessageGateway;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MessageRepositoryTest extends KernelTestCase
{
    private MessageRepository $messageRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->messageRepository = $container->get(MessageGateway::class);
    }

    static public function data(): array
    {
        $members = [MemberRepository::parse([
            'email' => 'arnaud+messageRepository@mail.com',
            'userIdentifier' => 'arnaud+messageRepository',
            'username' => 'Arnaud'
        ]), MemberRepository::parse([
            'email' => 'marie+messageRepository@mail.com',
            'userIdentifier' => 'marie+messageRepository',
            'username' => 'Marie'
        ])];

        $discussions = [DiscussionRepository::parse([
            'id' => '25b5e35d-ed71-4fe2-9ae9-178c88cc33b2',
            'name' => 'Discussion 1 messageRepository',
            'discussionMembers' => [
                [
                    'member' => $members[0],
                    'seen' => true,
                ],
                [
                    'member' => $members[1],
                    'seen' => false,
                ]
            ]
        ])];
        $messages = [MessageRepository::parse([
            'id' => '006fa199-1b70-4942-8333-29b6de1861ff',
            'created_at' => '2024-12-18 21:27:14.000000',
            'updated_at' => '2024-12-18 21:27:14.000000',
            'deleted' => false,
            'message' => 'Hello bro !',
            'discussion_id' => $discussions[0]->getId(),
            'email' => $members[0]->getEmail(),
            'userIdentifier' => $members[0]->getUserIdentifier(),
            'username' => $members[0]->getUsername(),
        ]), MessageRepository::parse([
            'id' => 'f49ff730-9a92-480f-9c33-735362482aa6',
            'created_at' => '2024-12-18 21:27:14.000000',
            'updated_at' => '2024-12-18 21:27:14.000000',
            'deleted' => false,
            'message' => 'Hello bro !',
            'discussion_id' => $discussions[0]->getId(),
            'email' => $members[1]->getEmail(),
            'userIdentifier' => $members[1]->getUserIdentifier(),
            'username' => $members[1]->getUsername(),
        ])];
        return [
            'members' => $members,
            'discussions' => $discussions,
            'messages' => $messages
        ];
    }

    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        return parent::createClient(array_merge($options, ['environment' => 'test']), $server);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testFindById()
    {
        $messages = $this->messageRepository->findBy([
            'id' => '006fa199-1b70-4942-8333-29b6de1861ff'
        ]);
        $this->assertCount(1, $messages);
        $this->assertEquals('006fa199-1b70-4942-8333-29b6de1861ff', $messages[0]->getId()->toString());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testParser()
    {
        $this->assertNull(MessageRepository::parse(null));
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testOrderById()
    {
        $messages = $this->messageRepository->findBy([
            'discussion.id' => '25b5e35d-ed71-4fe2-9ae9-178c88cc33b2'
        ], [
            'orderBy' => [
                'id' => 'ASC'
            ]
        ]);
        $this->assertCount(2, $messages);
        $this->assertEquals('006fa199-1b70-4942-8333-29b6de1861ff', $messages[0]->getId()->toString());
        $this->assertEquals('f49ff730-9a92-480f-9c33-735362482aa6', $messages[1]->getId()->toString());
    }
}
