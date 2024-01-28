<?php

namespace App\Entity;

use App\Repository\KeywordsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KeywordsRepository::class)]
class Keywords
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?int $keywordId = null;

    #[ORM\Column(length: 255)]
    private ?string $keywordValue = null;

    #[ORM\Column(length: 255)]
    private ?string $keywordCorrespondingValue = null;

    #[ORM\Column(length: 255)]
    private ?string $keywordConcernedObject = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $keywordType = null;
    

    public function getKeywordId(): ?int
    {
        return $this->keywordId;
    }

    public function setKeywordId(int $keywordId): static
    {
        $this->keywordId = $keywordId;

        return $this;
    }

    public function getKeywordValue(): ?string
    {
        return $this->keywordValue;
    }

    public function setKeywordValue(string $keywordValue): static
    {
        $this->keywordValue = $keywordValue;

        return $this;
    }

    public function getKeywordCorrespondingValue(): ?string
    {
        return $this->keywordCorrespondingValue;
    }

    public function setKeywordCorrespondingValue(string $keywordCorrespondingValue): static
    {
        $this->keywordCorrespondingValue = $keywordCorrespondingValue;

        return $this;
    }

    public function getKeywordConcernedObject(): ?string
    {
        return $this->keywordConcernedObject;
    }
    public function setKeywordConcernedObject(string $keywordConcernedObject): ?string
    {
        return $this->keywordConcernedObject = $keywordConcernedObject;
    }

    public function getKeywordType(): ?string
    {
        return $this->keywordType;
    }

    public function setKeywordType(?string $keywordType): static
    {
        $this->keywordType = $keywordType;

        return $this;
    }
}
