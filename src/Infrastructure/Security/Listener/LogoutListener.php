<?php

namespace App\Infrastructure\Security\Listener;

use App\Infrastructure\Security\Client\OpenIdClient;
use App\Infrastructure\Security\Dto\TokensBag;
use App\Infrastructure\Security\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

final class LogoutListener implements EventSubscriberInterface
{
    public function __construct(
        private OpenIdClient $openIdClient,
        private TokenStorageInterface $tokenStorage,
    ) {}

    public function logoutFromOpenidProvider(LogoutEvent $event): void
    {
        $token = $this->tokenStorage->getToken();

        $user = $token->getUser();
        if (!$user instanceof User) {
            return;
        }

        $tokens = $token->getAttribute(TokensBag::class);
        if (null === $tokens) {
            throw new \LogicException(sprintf('%s token attribute is empty', TokensBag::class));
        }

        $this->openIdClient->logout($tokens->getAccessToken(), $tokens->getRefreshToken());
    }

    public static function getSubscribedEvents(): array
    {
        return [LogoutEvent::class => 'logoutFromOpenidProvider'];
    }
}
