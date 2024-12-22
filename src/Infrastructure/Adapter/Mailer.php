<?php

namespace App\Infrastructure\Adapter;

use Messenger\Domain\Gateway\NotificationGateway;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class Mailer extends NotificationGateway
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TransportInterface    $transport)
    {
        parent::__construct();
    }

    public function invitesDiscussion(string $email, array $params = []): void
    {

        $email = (new Email())
            ->from('from@example.org')
            ->to($email)
            ->subject("Invitation to Join the Discussion " . $params['discussion']->getName())
            ->text("
Hello,

You have been invited to join a discussion titled " . $params['discussion']->getName() . ".
Messenger is a simple and intuitive online discussion platform designed to facilitate real-time communication and collaboration.

To join the discussion and start participating:

Click this link: " . $this->urlGenerator->generate('discussions_show', ['discussionId' => $params['discussion']->getId()]) . "

Create your account (it takes less than 2 minutes).

Access the discussion http://localhost:8000" . $params['discussion']->getName() . " and start exchanging ideas with other participants.

We are excited to welcome you to {nom_application} and look forward to seeing you participate in this discussion!

If you have any questions, feel free to contact us at {email_support}.

See you soon on Messenger!

The Messenger Team");
        $this->transport->send($email);
    }

    public function invitesMemberDiscussion(string $email, array $params = []): void
    {
        $email = (new Email())
            ->from('from@example.org')
            ->to($email)
            ->subject("Invitation to Join the Discussion " . $params['discussion']->getName())
            ->text("
Hello " . $params['member']->getUsername() . ",

You have been invited to join a discussion titled " . $params['discussion']->getName() . ".

To join the discussion:

Click this link: http://localhost:8000" . $this->urlGenerator->generate('discussions_show', ['discussionId' => $params['discussion']->getId()]) . "

Log in to your account.

Access the discussion " . $params['discussion']->getName() . " and start exchanging ideas with other participants.

We are excited to see you back on Messenger and look forward to your contributions to the discussion!

If you have any questions, feel free to contact us at {email_support}.

See you soon on Messenger!

The Messenger Team");
        $this->transport->send($email);
    }

    public function newMessage(string $email, array $params = []): void
    {
        $email = (new Email())
            ->from('from@example.org')
            ->to($email)
            ->subject("New Message in the Discussion " . $params['discussion']->getName() . " on Messenger")
            ->text("
Subject:

Hello" . ($params['member']->getUsername() ? (' ' . $params['member']->getUsername()) : '') . ",

A new message has been posted in the discussion " . $params['discussion']->getName() . " on Messenger.

To view the message and join the conversation:

Click this link: http://localhost:8000" . $this->urlGenerator->generate('discussions_show', ['discussionId' => $params['discussion']->getId()]) . "

Log in to your account.

Access the discussion " . $params['discussion']->getName() . " to read the latest updates and reply.

Stay connected and keep the conversation going!

If you have any questions, feel free to contact us at {email_support}.

See you soon on Messenger!

The Messenger Team");
        $this->transport->send($email);
    }
}
