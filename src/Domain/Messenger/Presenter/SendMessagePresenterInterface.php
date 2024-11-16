<?php

namespace App\Domain\Messenger\Presenter;

use App\Domain\Messenger\Response\SendMessageResponse;

interface SendMessagePresenterInterface extends PresenterInterface
{
    public function present(SendMessageResponse $response): void;

}
