<?php

namespace App\Tests\Domain\Messenger;

use App\Domain\Messenger\Entity\Discussion;
use App\Domain\Messenger\Entity\Message;
use App\Domain\Messenger\Presenter\SendMessagePresenterInterface;
use App\Domain\Messenger\Request\SendMessageRequest;
use App\Domain\Messenger\Response\SendMessageResponse;
use App\Domain\Messenger\UseCase\SendMessage;
use App\Domain\Security\Gateway\UserGateway;
use App\Infrastructure\Test\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Test\Adapter\Repository\MessageRepository;
use App\Infrastructure\Test\Adapter\Repository\UserRepository;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class SendMessageTest extends TestCase
{
    private SendMessagePresenterInterface $presenter;
    private UserGateway $userGateway;
    private DiscussionRepository $discussionGateway;
    private SendMessage $useCase;

    protected function setUp(): void
    {
        $this->presenter = new class() implements SendMessagePresenterInterface {
            public SendMessageResponse $response;

            public function present(SendMessageResponse $response): void
            {
                $this->response = $response;
            }
        };
        $this->userGateway = new UserRepository();
        $this->discussionGateway = new DiscussionRepository();
        $this->useCase = new SendMessage(new MessageRepository());
    }

    public function testSuccessful(): void
    {
        $messageContent ="message content";
        $username = 'username';
        $discussionId = '3feb781c-8a9d-4650-8390-99aaa60efcba';
        $request = SendMessageRequest::create(
            $messageContent,
            $this->discussionGateway->find($discussionId),
            $this->userGateway->findOneByUsername($username));

        $this->useCase->execute($request, $this->presenter);

        $this->assertInstanceOf(SendMessageResponse::class, $this->presenter->response);

        $this->assertInstanceOf(Discussion::class, $this->presenter->response->getDiscussion());
        $this->assertInstanceOf(Message::class, $this->presenter->response->getMessage());
        $this->assertEquals($discussionId, $this->presenter->response->getDiscussion()->getId()->toString());
        $this->assertEquals($messageContent, $this->presenter->response->getMessage()->getMessage());
        $this->assertEquals($username, $this->presenter->response->getMessage()->getAuthor()->getUsername());
    }


    /**
     * @dataProvider provideFailedValidationRequestsData
     * @param string|null $messageContent
     * @param string $discussionId
     * @param string $username
     * @return void
     */
    public function testFailedValidation(?string $messageContent, string $discussionId, string $username): void
    {
        $this->expectException(AssertionFailedException::class);
        SendMessageRequest::create(
            $messageContent,
            $this->discussionGateway->find($discussionId),
            $this->userGateway->findOneByUsername($username));
    }

    public function provideFailedValidationRequestsData(): \Generator
    {
        yield ["", '3feb781c-8a9d-4650-8390-99aaa60efcba', 'username'];
        yield ["", '3feb781c-8a9d-4650-8390-99aaa60efcba', 'username1'];
    }
}
