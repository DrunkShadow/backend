<?php

namespace App\Entity;

use App\Repository\ModelsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelsRepository::class)] 
class Models
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?string $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(nullable: true)]
    private ?bool $concernsProject = null;

    #[ORM\Column(nullable: true)]
    private ?bool $concernsWorker = null;

    #[ORM\Column]
    private ?bool $concernsEmail = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $Text): static
    {
        $this->text = $Text;

        return $this;
    }

    public function isConcernsProject(): ?bool
    {
        return $this->concernsProject;
    }

    public function setConcernsProject(?bool $concernsProject): static
    {
        $this->concernsProject = $concernsProject;

        return $this;
    }

    public function isConcernsWorker(): ?bool
    {
        return $this->concernsWorker;
    }

    public function setConcernsWorker(?bool $concernsWorker): static
    {
        $this->concernsWorker = $concernsWorker;

        return $this;
    }

    public function isConcernsEmail(): ?bool
    {
        return $this->concernsEmail;
    }

    public function setConcernsEmail(bool $concernsEmail): static
    {
        $this->concernsEmail = $concernsEmail;

        return $this;
    }
}
