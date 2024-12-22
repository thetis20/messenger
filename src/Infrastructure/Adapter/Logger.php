<?php

namespace App\Infrastructure\Adapter;

use Messenger\Domain\Gateway\LoggerInterface;

final readonly class Logger implements LoggerInterface
{
    public function __construct(private \Psr\Log\LoggerInterface $logger)
    {
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }
}
