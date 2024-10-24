<?php

namespace App\Entity;

use App\Repository\DevisImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: DevisImageRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class DevisImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    private ?string $imageName = null;

    #[Vich\UploadableField(mapping: 'categories', fileNameProperty: 'imageName')]
    #[Assert\Image(
        detectCorrupted:true,
        maxSize: '2M',
        notReadableMessage:"Le fichier est corrompu et ne peut pas être lu.",
        mimeTypesMessage: "Le fichier soumis n'est pas une image valide. Veuillez sélectionner un fichier image.",
    )]
    #[Assert\When(
        expression: 'this.getId() == null',
        constraints: [
            new Assert\NotBlank(message: 'L\'image ne peut pas être vide. Veuillez télécharger une image.'),
        ]
    )]
    private ?File $imageFile = null;

    #[ORM\ManyToOne(inversedBy: 'devisImages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devis $Devis = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getDevis(): ?Devis
    {
        return $this->Devis;
    }

    public function setDevis(?Devis $Devis): static
    {
        $this->Devis = $Devis;

        return $this;
    }

    /**
     * Get the value of imageFile
     *
     * @return ?File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * Set the value of imageFile
     *
     * @param ?File $imageFile
     *
     * @return self
     */
    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }
}