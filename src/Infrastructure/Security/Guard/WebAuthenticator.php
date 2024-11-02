<?php

namespace App\Infrastructure\Security\Guard;

use App\Domain\Security\Presenter\LoginPresenterInterface;
use App\Domain\Security\Request\LoginRequest;
use App\Domain\Security\Response\LoginResponse;
use App\Domain\Security\UseCase\Login;
use App\Infrastructure\Security\User;
use Assert\AssertionFailedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Twig\Environment;

class WebAuthenticator extends AbstractLoginFormAuthenticator implements LoginPresenterInterface
{
    private Login $login;
    private LoginResponse $response;

    public function __construct(Login $login)
    {
        $this->login = $login;
    }

    protected function getLoginUrl(Request $request): string
    {
        return "/login";
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->get('username', '');
        $plainPassword = $request->get('password', '');

        try {

            $this->login->execute(new LoginRequest($username, $plainPassword), $this);
        } catch (AssertionFailedException $exception) {
            throw new AuthenticationException($exception->getMessage());
        }

        if ($this->response->getUser() === null) {
            throw new AuthenticationException('User not found.');
        }

        if (!$this->response->isPasswordValid()) {
            throw new AuthenticationException('Wrong credentials !');
        }
        $response = $this->response;

        return new SelfValidatingPassport(
            new UserBadge($this->response->getUser()->getId(), function () use ($response) {
                return new User($response->getUser());
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $request->getSession()->getFlashBag()->add("success", 'logged');
        return new RedirectResponse('/');
    }

    public function present(LoginResponse $response): void
    {
        $this->response = $response;
    }
}
