<?php

namespace App\UserInterface\ViewModel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginViewModel
{
    private string $lastUsername;
    private ?string $errorMessage;

    public static function fromWebAuthenticator(Request $request, AuthenticationException $exception): self
    {
        return new self($request->get('password'), $exception);
    }

    public static function fromAuthenticationUtils(AuthenticationUtils $authenticationUtils): self
    {
        return new self($authenticationUtils->getLastUsername(),
            $authenticationUtils->getLastAuthenticationError());
    }

    public function __construct(string $lastUsername, ?AuthenticationException $error)
    {
        $this->lastUsername = $lastUsername;
        $this->errorMessage = $error?->getMessage();
    }

    public function getLastUsername(): string
    {
        return $this->lastUsername;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}
