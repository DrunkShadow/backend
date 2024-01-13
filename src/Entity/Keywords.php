<?php

namespace App\Entity;

use App\Repository\KeywordsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KeywordsRepository::class)]
class Keywords
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\Column(length: 255)]
    private ?string $concernedObject = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getConcernedObject(): ?string
    {
        return $this->concernedObject;
    }

    public function setConcernedObject(string $concernedObject): static
    {
        $this->concernedObject = $concernedObject;

        return $this;
    }
}
