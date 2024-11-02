<?php

namespace App\Tests\Domain\Security;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Presenter\RegistrationPresenterInterface;
use App\Domain\Security\Request\RegistrationRequest;
use App\Domain\Security\Response\RegistrationResponse;
use App\Domain\Security\UseCase\Registration;
use App\Infrastructure\Test\Adapter\Repository\UserRepository;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class RegistrationTest extends TestCase
{
    private RegistrationPresenterInterface $presenter;
    private Registration $useCase;

    protected function setUp(): void
    {
        $this->presenter = new class() implements RegistrationPresenterInterface {
            public RegistrationResponse $response;

            public function present(RegistrationResponse $response): void
            {
                $this->response = $response;
            }
        };
        $userGateway = new UserRepository();
        $this->useCase = new Registration($userGateway);
    }

    public function testSuccessful(): void
    {
        $email = "username-new@email.com";
        $username = "username-new";
        $password = "password";
        $request = RegistrationRequest::create($email, $username, $password);

        $this->useCase->execute($request, $this->presenter);

        $this->assertInstanceOf(RegistrationResponse::class, $this->presenter->response);

        $this->assertInstanceOf(User::class, $this->presenter->response->getUser());
        $this->assertInstanceOf(Uuid::class, $this->presenter->response->getUser()->getId());
        $this->assertEquals($email, $this->presenter->response->getUser()->getEmail());
        $this->assertEquals($username, $this->presenter->response->getUser()->getUsername());
        $this->assertTrue(password_verify($password, $this->presenter->response->getUser()->getPassword()));
    }


    /**
     * @dataProvider provideFailedRequestsData
     * @param string $email
     * @param string $username
     * @param string $plainPassword
     * @return void
     */
    public function testFailed(string $email, string $username, string $plainPassword): void
    {
        $request = RegistrationRequest::create($email, $username, $plainPassword);
        $this->expectException(AssertionFailedException::class);
        $this->useCase->execute($request, $this->presenter);
    }

    public function provideFailedRequestsData(): \Generator
    {
        yield ["", "username", "password"];
        yield ["email", "username", "password"];
        yield ["email@email.com", "", "password"];
        yield ["email@email.com", "", "password"];
        yield ["email@email.com", "username", ""];
        yield ["email@email.com", "username", "fail"];
        yield ["used@email.com", "username", "password"];
        yield ["email@email.com", "used-username", "password"];
    }
}
