<?php

namespace App\UserInterface;

use App\Domain\Security\Presenter\RegistrationPresenterInterface;
use App\Domain\Security\Response\RegistrationResponse;

class RegistrationPresenter implements RegistrationPresenterInterface
{

    public function present(RegistrationResponse $response): void
    {

    }
}
