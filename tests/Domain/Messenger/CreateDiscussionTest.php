<?php

namespace App\Tests\Domain\Messenger;

use App\Domain\Messenger\Entity\Discussion;
use App\Domain\Messenger\Presenter\CreateDiscussionPresenterInterface;
use App\Domain\Messenger\Request\CreateDiscussionRequest;
use App\Domain\Messenger\Response\CreateDiscussionResponse;
use App\Domain\Messenger\UseCase\CreateDiscussion;
use App\Domain\Security\Gateway\UserGateway;
use App\Infrastructure\Doctrine\Entity\DoctrineUser;
use App\Infrastructure\Security\User;
use App\Infrastructure\Test\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Test\Adapter\Repository\UserRepository;
use Assert\AssertionFailedException;
use Doctrine\Common\Collections\ArrayCollection;
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
        $this->useCase = new CreateDiscussion(new DiscussionRepository());
    }

    public function testSuccessful(): void
    {
        $request = CreateDiscussionRequest::create(
            "discussion name",
            new ArrayCollection([$this->userGateway->findOneByUsername('username1')]),
            new User($this->userGateway->findOneByUsername('username')));

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
     * @param ArrayCollection<DoctrineUser> $users
     * @param User $currentUser
     * @return void
     */
    public function testFailedValidation(string $name, array $users, ?string $currentUser): void
    {
        $users = new ArrayCollection(array_map(function ($user) {
            return $this->userGateway->findOneByUsername($user);
        }, $users));
        $currentUser = $currentUser ? new User($this->userGateway->findOneByUsername('username')) : $currentUser;
        $this->expectException(AssertionFailedException::class);
        CreateDiscussionRequest::create($name, $users, $currentUser);
    }

    public function provideFailedValidationRequestsData(): \Generator
    {
        yield ["", ['username1'], 'username'];
        yield ["name", [], 'username'];
        yield ["name", ['username-unknown'], 'username'];
        yield ["name", ['username'], 'username'];
    }
}
