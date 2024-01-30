<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttachmentRepository::class)]
class Attachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $attachmentId = null;

    #[ORM\Column]
    private ?int $attachmentContainingEmailId = null;

    #[ORM\Column(length: 255)]
    private ?string $attachmentName = null;

    #[ORM\Column(length: 255)]
    private ?string $attachmentFilePath = null;

    public function getAttachmentId(): ?int
    {
        return $this->attachmentId;
    }

    public function setAttachmentId(int $attachmentId): static
    {
        $this->attachmentId = $attachmentId;

        return $this;
    }

    public function getAttachmentContainingEmailId(): ?int
    {
        return $this->attachmentContainingEmailId;
    }

    public function setAttachmentContainingEmailId(int $attachmentContainingEmailId): static
    {
        $this->attachmentContainingEmailId = $attachmentContainingEmailId;

        return $this;
    }

    public function getAttachmentName(): ?string
    {
        return $this->attachmentName;
    }

    public function setAttachmentName(string $attachmentName): static
    {
        $this->attachmentName = $attachmentName;

        return $this;
    }
    public function getAttachmentFilePath(): ?string
    {
        return $this->attachmentFilePath;
    }

    public function setAttachmentFilePath(string $attachmentFilePath): static
    {
        $this->attachmentFilePath = $attachmentFilePath;

        return $this;
    }
}
