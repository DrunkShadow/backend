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
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $name = null;

    #[ORM\Column(length: 40)]
    private ?string $lastName = null;

    #[ORM\Column(length: 40)]
    private ?string $title = null;

    #[ORM\Column(type: Types::BLOB)]
    private $signature = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $Name): static
    {
        $this->name = $Name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $LastName): static
    {
        $this->lastName = $LastName;

        return $this;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $Title): static
    {
        $this->title = $Title;

        return $this;
    }

    public function getSignature()
    {
        if (is_resource($this->signature)) {
            return base64_encode(stream_get_contents($this->signature));
        }
    
        return base64_encode($this->signature);
    }

    public function setSignature($signature): static
    {
        $this->signature = $signature;
        return $this;
    }
}
