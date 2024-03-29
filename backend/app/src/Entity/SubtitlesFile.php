<?php

namespace App\Entity;

use App\Repository\SubtitlesFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubtitlesFileRepository::class)]
class SubtitlesFile {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $storage_path = null;

    #[ORM\Column]
    private ?int $created_ts = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): static {
        $this->name = $name;

        return $this;
    }

    public function getStoragePath(): ?string {
        return $this->storage_path;
    }

    public function setStoragePath(string $storage_path): static {
        $this->storage_path = $storage_path;

        return $this;
    }

    public function getCreatedTs(): ?int {
        return $this->created_ts;
    }

    public function setCreatedTs(int $created_ts): static {
        $this->created_ts = $created_ts;

        return $this;
    }
}
