<?php

namespace App\UserInterface\ViewModel;


use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Entity\DiscussionMember;
use Messenger\Domain\Entity\Member;
use Messenger\Domain\Entity\UserInterface;
use Symfony\Component\Uid\Uuid;

class DiscussionViewModel extends Discussion
{
    private UserInterface $currentUser;

    static public function create(Discussion $discussion, UserInterface $user): DiscussionViewModel
    {
        return new self($discussion->getId(), $discussion->getName(), $discussion->getDiscussionMembers(), $user);
    }

    /**
     * @param DiscussionMember[] $discussionMembers
     */
    public function __construct(Uuid $id, string $name, array $discussionMembers, UserInterface $user)
    {
        parent::__construct($id, $name, $discussionMembers);
        $this->currentUser = $user;
    }

    /**
     * @return Member[]
     */
    public function getOtherMembers(): array
    {
        $members = [];
        foreach ($this->getDiscussionMembers() as $dm) {
            if ($dm->getMember()->getEmail() === $this->currentUser->getEmail()) {
                continue;
            }
            $members[] = $dm->getMember();
        }
        return $members;
    }

    public function isSeen(): bool
    {
        foreach ($this->getDiscussionMembers() as $dm) {
            if ($dm->getMember()->getEmail() === $this->currentUser->getEmail()) {
                return $dm->isSeen();
            }
        }
        return false;
    }
}
