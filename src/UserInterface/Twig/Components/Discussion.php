<?php

namespace App\UserInterface\Twig\Components;

use Messenger\Domain\Entity\Member;
use Messenger\Domain\Entity\UserInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Discussion
{
    public \Messenger\Domain\Entity\Discussion $discussion;
    public UserInterface $user;
    public string $view = 'link';

    /**
     * @return Member[]
     */
    public function getOtherMembers(): array
    {
        $members = [];
        foreach ($this->discussion->getDiscussionMembers() as $dm) {
            if ($dm->getMember()->getEmail() === $this->user->getEmail()) {
                continue;
            }
            $members[] = $dm->getMember();
        }
        return $members;
    }

    public function isSeen(): bool
    {
        foreach ($this->discussion->getDiscussionMembers() as $dm) {
            if ($dm->getMember()->getEmail() === $this->user->getEmail()) {
                return $dm->isSeen();
            }
        }
        // @codeCoverageIgnoreStart
        return false;
        // @codeCoverageIgnoreEnd
    }

    use DefaultActionTrait;
}
