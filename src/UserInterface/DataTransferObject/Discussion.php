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

    /**
     * @return string[]
     */
    public function getEmails(): array
    {
        return $this->emails;
    }

    /**
     * @param string[] $emails
     * @return void
     */
    public function setEmails(array $emails): void
    {
        $this->emails = $emails;
    }

}
