<?php

namespace App\Domain\Messenger\Gateway;

use App\Domain\Messenger\Presenter\PresenterInterface;
use App\Domain\Messenger\Response\ResponseInterface;
use App\Domain\Security\Entity\User;

interface NotificationGateway
{
    /**
     * @param string $key
     * @param User $user
     * @param ResponseInterface $response
     * @return void
     */
    public function send(string $key, User $user, ResponseInterface $response): void;
}
