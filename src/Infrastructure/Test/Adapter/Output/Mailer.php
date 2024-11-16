<?php

namespace App\Infrastructure\Test\Adapter\Output;

use App\Domain\Messenger\Gateway\NotificationGateway;
use App\Domain\Messenger\Response\ResponseInterface;
use App\Domain\Security\Entity\User;

class Mailer implements NotificationGateway
{
    private array $notifications = [];

    public function send(string $key, User $user, ResponseInterface $response): void
    {
        $notifications[] = [
            'key' => $key,
            'user' => $user,
            'response' => $response,
        ];
    }

    public function getNotifications(): array
    {
        return $this->notifications;
    }
}
