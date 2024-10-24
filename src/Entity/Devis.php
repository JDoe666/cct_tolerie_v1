<?php

namespace App\Entity;

use App\Entity\Logs\DevisLogs;
use App\Entity\Traits\DateTimeTrait;
use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Devis
{
    use DateTimeTrait;

    public const STATUS_WAITING = 'En attente';
    public const STATUS_ACCEPTED = 'Accepté';
    public const STATUS_FINISHED = 'Terminé';
    public const STATUS_CANCELED = 'Annulé';
    public const STATUS_DENIED = 'Refusé';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\Choice(
        choices: [
            self::STATUS_WAITING,
            self::STATUS_ACCEPTED,
            self::STATUS_FINISHED,
            self::STATUS_CANCELED,
            self::STATUS_DENIED,
        ]
    )]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?User $user = null;

    /**
     * @var Collection<int, DevisImage>
     */
    #[ORM\OneToMany(targetEntity: DevisImage::class, mappedBy: 'Devis', orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[Assert\Count(min: 1, minMessage: 'Veuillez envoyer au minimum une image.')]
    #[Assert\Valid]
    private Collection $devisImages;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserAddress $address = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $canceledBy = null;

    /**
     * @var Collection<int, DevisLogs>
     */
    #[ORM\OneToMany(targetEntity: DevisLogs::class, mappedBy: 'Devis')]
    private Collection $devisLogs;

    public function __construct()
    {
        $this->devisImages = new ArrayCollection();
        $this->devisLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return ?string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param ?string $status
     *
     * @return self
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, DevisImage>
     */
    public function getDevisImages(): Collection
    {
        return $this->devisImages;
    }

    public function addDevisImage(DevisImage $devisImage): static
    {
        if (!$this->devisImages->contains($devisImage)) {
            $this->devisImages->add($devisImage);
            $devisImage->setDevis($this);
        }

        return $this;
    }

    public function removeDevisImage(DevisImage $devisImage): static
    {
        if ($this->devisImages->removeElement($devisImage)) {
            // set the owning side to null (unless already changed)
            if ($devisImage->getDevis() === $this) {
                $devisImage->setDevis(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?UserAddress
    {
        return $this->address;
    }

    public function setAddress(?UserAddress $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCanceledBy(): ?string
    {
        return $this->canceledBy;
    }

    public function setCanceledBy(?string $canceledBy): static
    {
        $this->canceledBy = $canceledBy;

        return $this;
    }

    /**
     * @return Collection<int, DevisLogs>
     */
    public function getDevisLogs(): Collection
    {
        return $this->devisLogs;
    }

    public function addDevisLog(DevisLogs $devisLog): static
    {
        if (!$this->devisLogs->contains($devisLog)) {
            $this->devisLogs->add($devisLog);
            $devisLog->setDevis($this);
        }

        return $this;
    }

    public function removeDevisLog(DevisLogs $devisLog): static
    {
        if ($this->devisLogs->removeElement($devisLog)) {
            // set the owning side to null (unless already changed)
            if ($devisLog->getDevis() === $this) {
                $devisLog->setDevis(null);
            }
        }

        return $this;
    }
}