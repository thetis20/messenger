<?php

namespace App\Tests\Domain\Messenger;

use App\Domain\Messenger\Entity\Discussion;
use App\Domain\Messenger\Gateway\DiscussionGateway;
use App\Domain\Messenger\Presenter\CreateDiscussionPresenterInterface;
use App\Domain\Messenger\Request\CreateDiscussionRequest;
use App\Domain\Messenger\UseCase\CreateDiscussion;
use App\Domain\Security\Gateway\UserGateway;
use App\Domain\Security\Response\CreateDiscussionResponse;
use App\Infrastructure\Test\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Test\Adapter\Repository\UserRepository;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class CreateDiscussionTest extends TestCase
{
    private CreateDiscussionPresenterInterface $presenter;
    private UserGateway $userGateway;
    private CreateDiscussion $useCase;

    protected function setUp(): void
    {
        $this->presenter = new class() implements CreateDiscussionPresenterInterface {
            public CreateDiscussionResponse $response;

            public function present(CreateDiscussionResponse $response): void
            {
                $this->response = $response;
            }
        };
        $this->userGateway = new UserRepository();
        $this->useCase = new CreateDiscussion($this->userGateway, new DiscussionRepository());
    }

    public function testSuccessful(): void
    {
        $request = CreateDiscussionRequest::create("discussion name", "username; username1", $this->userGateway);

        $this->useCase->execute($request, $this->presenter);

        $this->assertInstanceOf(CreateDiscussionResponse::class, $this->presenter->response);

        $this->assertInstanceOf(Discussion::class, $this->presenter->response->getDiscussion());
        $this->assertCount(2, $this->presenter->response->getDiscussion()->getMembers());
        $usernames = array_map(function ($member) {
            return $member->getUsername();
        }, $this->presenter->response->getDiscussion()->getMembers());
        $this->assertContains('username', $usernames);
        $this->assertContains('username1', $usernames);
    }


    /**
     * @dataProvider provideFailedValidationRequestsData
     * @param string $name
     * @param string $usernames
     * @return void
     */
    public function testFailedValidation(string $name, string $usernames): void
    {
        $request = CreateDiscussionRequest::create($name, $usernames, $this->userGateway);
        $this->expectException(AssertionFailedException::class);
        $this->useCase->execute($request, $this->presenter);
    }

    public function provideFailedValidationRequestsData(): \Generator
    {
        yield ["", "username"];
        yield ["name", ""];
        yield ["name", "username;"];
        yield ["name", "username;username-unknown"];
    }
}
