<?php

namespace App\Entity;

use App\Repository\ModelsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModelsRepository::class)] 
class Models
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length: 255)]
    private ?string $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

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
}
