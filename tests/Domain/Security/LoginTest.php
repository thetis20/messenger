<?php

namespace App\Tests\Domain\Security;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Presenter\LoginPresenterInterface;
use App\Domain\Security\Request\LoginRequest;
use App\Domain\Security\Response\LoginResponse;
use App\Domain\Security\UseCase\Login;
use App\Infrastructure\Test\Adapter\Repository\UserRepository;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private LoginPresenterInterface $presenter;
    private Login $useCase;

    protected function setUp(): void
    {
        $this->presenter = new class() implements LoginPresenterInterface {
            public LoginResponse $response;

            public function present(LoginResponse $response): void
            {
                $this->response = $response;
            }
        };
        $userGateway = new UserRepository();
        $this->useCase = new Login($userGateway);
    }

    public function testSuccessful(): void
    {
        $request = LoginRequest::create("username", "password");

        $this->useCase->execute($request, $this->presenter);

        $this->assertInstanceOf(LoginResponse::class, $this->presenter->response);

        $this->assertInstanceOf(User::class, $this->presenter->response->getUser());
        $this->assertEquals('username', $this->presenter->response->getUser()->getUsername());
        $this->assertTrue($this->presenter->response->isPasswordValid());
    }


    /**
     * @dataProvider provideFailedValidationRequestsData
     * @param string $username
     * @param string $plainPassword
     * @return void
     */
    public function testFailedValidation(string $username, string $plainPassword): void
    {
        $request = LoginRequest::create($username, $plainPassword);
        $this->expectException(AssertionFailedException::class);
        $this->useCase->execute($request, $this->presenter);
    }

    public function provideFailedValidationRequestsData(): \Generator
    {
        yield ["", "password"];
        yield ["username", ""];
    }

    public function testIfUsernameNotFound(): void
    {
        $request = LoginRequest::create("username-not-found", "password");

        $this->useCase->execute($request, $this->presenter);

        $this->assertInstanceOf(LoginResponse::class, $this->presenter->response);

        $this->assertEquals(null, $this->presenter->response->getUser());
        $this->assertFalse($this->presenter->response->isPasswordValid());
    }

    public function testIfPasswordIsWrong(): void
    {
        $request = LoginRequest::create("username", "wrong-password");

        $this->useCase->execute($request, $this->presenter);

        $this->assertInstanceOf(LoginResponse::class, $this->presenter->response);
        $this->assertInstanceOf(User::class, $this->presenter->response->getUser());
        $this->assertFalse($this->presenter->response->isPasswordValid());
    }
}
