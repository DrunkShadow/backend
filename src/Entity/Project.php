<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $projectId = null;

    #[ORM\Column(length: 255)]
    private ?string $projectName = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 3)]
    private ?string $projectBudget = null;

    #[ORM\Column(length: 255)]
    private ?string $projectCategory = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $projectDate = null;

    #[ORM\Column(length: 255)]
    private ?string $projectLink = null;

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    public function setProjectId(int $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function setProjectName(string $projectName): static
    {
        $this->projectName = $projectName;

        return $this;
    }

    public function getProjectBudget(): ?string
    {
        return $this->projectBudget;
    }

    public function setProjectBudget(string $projectBudget): static
    {
        $this->projectBudget = $projectBudget;

        return $this;
    }

    public function getProjectCategory(): ?string
    {
        return $this->projectCategory;
    }

    public function setProjectCategory(string $projectCategory): static
    {
        $this->projectCategory = $projectCategory;

        return $this;
    }

    public function getProjectDate(): ?\DateTimeInterface
    {
        return $this->projectDate;
    }

    public function setProjectDate(\DateTimeInterface $projectDate): static
    {
        $this->projectDate = $projectDate;

        return $this;
    }

    public function getProjectLink(): ?string
    {
        return $this->projectLink;
    }

    public function setProjectLink(string $projectLink): static
    {
        $this->projectLink = $projectLink;

        return $this;
    }
}
