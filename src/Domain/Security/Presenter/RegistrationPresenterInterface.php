<?php

namespace App\Domain\Security\Presenter;

use App\Domain\Security\Response\RegistrationResponse;

interface RegistrationPresenterInterface
{
    public function present(RegistrationResponse $response): void;

}
