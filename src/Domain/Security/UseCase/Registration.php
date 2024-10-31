<?php

namespace App\Domain\Security\UseCase;

use App\Domain\Security\Entity\User;
use App\Domain\Security\Gateway\UserGateway;
use App\Domain\Security\Presenter\RegistrationPresenterInterface;
use App\Domain\Security\Request\RegistrationRequest;
use App\Domain\Security\Response\RegistrationResponse;

class Registration
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
     * @param RegistrationRequest $request
     * @param RegistrationPresenterInterface $presenter
     * @return void
     */
    public function execute(RegistrationRequest $request, RegistrationPresenterInterface $presenter): void
    {
        $request->validate($this->userGateway);
        $user = User::fromRegistration($request->getEmail(),
            $request->getUsername(),
            $request->getPlainPassword());
        $presenter->present(new RegistrationResponse($user));
    }

}
