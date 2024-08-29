<?php

namespace App\Entity\Logs;

use App\Entity\Devis;
use App\Entity\User;
use App\Repository\Logs\DevisLogsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DevisLogsRepository::class)]
class DevisLogs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'devisLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devis $devis = null;

    #[ORM\ManyToOne(inversedBy: 'devisLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $administration = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $action = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private array $details = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): static
    {
        $this->devis = $devis;

        return $this;
    }

    public function getAdministration(): ?User
    {
        return $this->administration;
    }

    public function setAdministration(?User $administration): static
    {
        $this->administration = $administration;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function setDetails(array $details): static
    {
        $this->details = $details;

        return $this;
    }
}