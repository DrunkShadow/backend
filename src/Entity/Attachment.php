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

    #[ORM\Column(type: Types::BLOB)]
    private $attachmentFile = null;

    #[ORM\Column]
    private ?int $attachmentContainingEmailId = null;

    public function getAttachmentId(): ?int
    {
        return $this->attachmentId;
    }

    public function setAttachmentId(int $attachmentId): static
    {
        $this->attachmentId = $attachmentId;

        return $this;
    }

    public function getAttachmentFile()
    {
        return $this->attachmentFile;
    }

    public function setAttachmentFile($attachmentFile): static
    {
        $this->attachmentFile = $attachmentFile;

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
}
