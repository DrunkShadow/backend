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
    private ?string $modelId = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $modelText = null;

    #[ORM\Column(nullable: true)]
    private ?bool $modelConcernsProject = null;

    #[ORM\Column(nullable: true)]
    private ?bool $modelConcernsWorker = null;

    #[ORM\Column]
    private ?bool $modelConcernsEmail = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $modelEmailAttachment = null;

    public function getModelId(): ?string
    {
        return $this->modelId;
    }

    public function setModelId(string $modelId): static
    {
        $this->modelId = $modelId;

        return $this;
    }

    public function getModelText(): ?string
    {
        return $this->modelText;
    }

    public function setModelText(string $modelText): static
    {
        $this->modelText = $modelText;

        return $this;
    }

    public function isModelConcernsProject(): ?bool
    {
        return $this->modelConcernsProject;
    }

    public function setModelConcernsProject(?bool $modelConcernsProject): static
    {
        $this->modelConcernsProject = $modelConcernsProject;

        return $this;
    }

    public function isModelConcernsWorker(): ?bool
    {
        return $this->modelConcernsWorker;
    }

    public function setModelConcernsWorker(?bool $modelConcernsWorker): static
    {
        $this->modelConcernsWorker = $modelConcernsWorker;

        return $this;
    }

    public function isModelConcernsEmail(): ?bool
    {
        return $this->modelConcernsEmail;
    }

    public function setModelConcernsEmail(bool $modelConcernsEmail): static
    {
        $this->modelConcernsEmail = $modelConcernsEmail;

        return $this;
    }

    public function getModelEmailAttachment(): ?array
    {
        return $this->modelEmailAttachment;
    }

    public function setModelEmailAttachment(?array $modelEmailAttachment): static
    {
        $this->modelEmailAttachment = $modelEmailAttachment;

        return $this;
    }
}
