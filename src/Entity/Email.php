<?php

namespace App\Entity;

use App\Repository\EmailRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: EmailRepository::class)]
class Email
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $emailId = null;

    #[ORM\Column(length: 255)]
    private ?string $emailSource = null;

    #[ORM\Column(length: 255)]
    private ?string $emailTarget = null;

    #[ORM\Column(length: 255)]
    private ?string $emailSubject = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $emailText = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $emailDate = null;

    public function getEmailId(): ?int
    {
        return $this->emailId;
    }

    public function setEmailId(int $emailId): static
    {
        $this->emailId = $emailId;

        return $this;
    }

    public function getEmailSource(): ?string
    {
        return $this->emailSource;
    }

    public function setEmailSource(string $emailSource): static
    {
        $this->emailSource = $emailSource;

        return $this;
    }

    public function getEmailTarget(): ?string
    {
        return $this->emailTarget;
    }

    public function setEmailTarget(string $emailTarget): static
    {
        $this->emailTarget = $emailTarget;

        return $this;
    }
    public function getEmailSubject(): ?string
    {
        return $this->emailSubject;
    }

    public function setEmailSubject(string $emailSubject): static
    {
        $this->emailSubject = $emailSubject;

        return $this;
    }

    public function getEmailText(): ?string
    {
        return $this->emailText;
    }

    public function setEmailText(string $emailText): static
    {
        $this->emailText = $emailText;

        return $this;
    }

    public function getEmailDate(): ?\DateTimeInterface
    {
        return $this->emailDate;
    }

    public function setEmailDate(\DateTimeInterface $emailDate): static
    {
        $this->emailDate = $emailDate;

        return $this;
    }
}
