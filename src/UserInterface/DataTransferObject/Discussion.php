<?php

namespace App\UserInterface\DataTransferObject;

class Discussion
{
    /** @var string|null */
    private ?string $name = null;
    /** @var string[] */
    private array $emails;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getEmails(): array
    {
        return $this->emails;
    }

    public function setEmails(array $emails): void
    {
        $this->emails = $emails;
    }

}
