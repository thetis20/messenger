<?php

namespace App\Domain\Security\Presenter;

use App\Domain\Security\Response\LoginResponse;

interface LoginPresenterInterface
{
    public function present(LoginResponse $response): void;

}
