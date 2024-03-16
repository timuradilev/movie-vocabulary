<?php

namespace App\Entity;

use App\Repository\WordRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WordRepository::class)]
#[ORM\Index(name: 'value_created_ts', columns: ['value', 'created_ts'])]
class Word {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $created_ts = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?SubtitlesFile $subtitlesFile = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getValue(): ?string {
        return $this->value;
    }

    public function setValue(string $value): static {
        $this->value = $value;

        return $this;
    }

    public function getCreatedTs(): ?int {
        return $this->created_ts;
    }

    public function setCreatedTs(int $created_ts): static {
        $this->created_ts = $created_ts;

        return $this;
    }

    public function getSubtitlesFile(): ?SubtitlesFile {
        return $this->subtitlesFile;
    }

    public function setSubtitlesFile(?SubtitlesFile $subtitlesFile): static {
        $this->subtitlesFile = $subtitlesFile;

        return $this;
    }
}
