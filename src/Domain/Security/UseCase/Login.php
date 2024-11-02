<?php

namespace App\Domain\Security\UseCase;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use App\Domain\Security\Presenter\LoginPresenterInterface;
use App\Domain\Security\Request\LoginRequest;
use App\Domain\Security\Response\LoginResponse;

class Login
{
    /** @var UserGateway */
    private UserGateway $userGateway;

    /**
     * @param UserGateway $userGateway
     */
    public function __construct(UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
    }

    /**
     * @param LoginRequest $request
     * @param LoginPresenterInterface $presenter
     * @return void
     */
    public function execute(LoginRequest $request, LoginPresenterInterface $presenter): void
    {
        $request->validate($this->userGateway);

        $user = $this->userGateway->findOneByUsername($request->getUsername());
        $passwordValid = $user === null ? false : password_verify($request->getPlainPassword(), $user->getPassword());

        $presenter->present(new LoginResponse($user, $passwordValid));
    }

}
