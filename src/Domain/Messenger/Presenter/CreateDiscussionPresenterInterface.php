<?php

namespace App\Domain\Messenger\Presenter;

use App\Domain\Messenger\Response\CreateDiscussionResponse;

interface CreateDiscussionPresenterInterface
{
    public function present(CreateDiscussionResponse $response): void;

}
