<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Repository\WorkerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkerRepository::class)]
class Worker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $workerId = null;

    #[ORM\Column(length: 40)]
    private ?string $workerName = null;

    #[ORM\Column(length: 40)]
    private ?string $workerLastName = null;

    #[ORM\Column(length: 40)]
    private ?string $workerTitle = null;

    #[ORM\Column(length: 255)]
    private $workerSignature = null;

    #[ORM\Column(length: 255)]
    private ?string $workerEmail = null;


    public function getWorkerId(): ?int
    {
        return $this->workerId;
    }

    public function getWorkerName(): ?string
    {
        return $this->workerName;
    }

    public function setWorkerName(string $workerName): static
    {
        $this->workerName = $workerName;

        return $this;
    }

    public function getWorkerLastName(): ?string
    {
        return $this->workerLastName;
    }

    public function setWorkerLastName(string $workerLastName): static
    {
        $this->workerLastName = $workerLastName;

        return $this;
    }

    public function setWorkerId(int $workerId): static
    {
        $this->workerId = $workerId;

        return $this;
    }

    public function getWorkerTitle(): ?string
    {
        return $this->workerTitle;
    }

    public function setWorkerTitle(string $workerTitle): static
    {
        $this->workerTitle = $workerTitle;

        return $this;
    }

    public function getWorkerSignature() : ?string
    {
        return $this->workerSignature;
    
    }

    public function setWorkerSignature($workerSignature): static
    {
        $this->workerSignature = $workerSignature;
        return $this;
    }

    public function getWorkerEmail(): ?string
    {
        return $this->workerEmail;
    }

    public function setWorkerEmail(string $workerEmail): static
    {
        $this->workerEmail = $workerEmail;

        return $this;
    }
}
