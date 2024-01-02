<?php

namespace App\Entity;

use App\Entity\Candidature;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\FormationController;
use App\Repository\FormationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use JsonSerializable;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:Formation']],
    denormalizationContext: ['groups' => ['write:Formation']],
    operations: [
        new Post(),
        new Put(),
        new Get(),
        new Delete(),
        new GetCollection(),
        new Get(
            name: 'Accepter une candidature',
            uriTemplate: '/candidature/{id}/accepte',
            controller: FormationController::class . '::AcceptCandidature',
            requirements: ['id' => '\d+'],
            description: 'Cet endpoint permet d\'accepter une candidature ',
            read: false,
            deserialize: false
        ),
        new Get(
            name: 'Refuser une candidature',
            uriTemplate: '/candidature/{id}/refuse',
            controller: FormationController::class . '::RefuseCandidature',
            requirements: ['id' => '\d+'],
            description: 'Cet endpoint permet de refuserer une candidature ',
            read: false,
            deserialize: false
        ),
        new Get(
            name: 'Liste des candidatures acceptees par formation',
            uriTemplate: '/formations/{id}/accepted-candidatures',
            controller: FormationController::class . '::FormationAcceptedCandidatures',
            requirements: ['id' => '\d+'],
            description: 'Cet endpoint permet de recuperer les candidatures acceptées dans une formations donnée ',
            read: false,
            deserialize: false
        ),
        new Get(
            name: 'Liste des candidatures refusees  par formation',
            uriTemplate: '/formations/{id}/refused-candidatures',
            controller: FormationController::class . '::FormationRefusedCandidatures',
            requirements: ['id' => '\d+'],
            description: 'Cet endpoint permet de recuperer les candidatures refusées dans une formations donnée ',
            read: false,
            deserialize: false
        ),
        new Get(
            name: 'cloturer une formation',
            uriTemplate: '/formations/{id}/close',
            controller: FormationController::class . '::closeFormation',
            requirements: ['id' => '\d+'],
            description: 'Cet endpoint permet de cloturer une formations donnée ',
            read: false,
            deserialize: false,
        ),
    ],
)]
class Formation implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:Formation'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:Formation', 'write:Formation'])]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:Formation', 'write:Formation'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:Formation', 'write:Formation'])]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:Formation', 'write:Formation'])]
    private ?\DateTimeInterface $dateCloture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:Formation', 'write:Formation'])]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:Formation', 'write:Formation'])]
    private ?string $duree = null;

    #[ORM\Column]
    #[Groups(['read:Formation', 'write:Formation'])]
    private ?bool $isDeleted = false;

    #[ORM\Column]
    #[Groups(['read:Formation', 'write:Formation'])]
    private ?bool $isFenced = false;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    #[Groups(['read:Formation', 'write:Formation'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Candidature::class, orphanRemoval: true)]
    private Collection $candidatures;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

    public function jsonSerialize(): array
    {
        return [
            'libelle' => $this->getLibelle(),
            'description' => $this->getDescription(),
            'image' => $this->getImage(),
            'dateCloture' => $this->getDateCloture(),
            'dateDebut' => $this->getDateDebut(),
            'duree' => $this->getDuree(),
            'isDeleted' => $this->isIsdeleted(),
            'isFenced' => $this->isIsFenced(),
            'user' => $this->getUser(),
            'createdAt' => $this->getCreatedAt()->format('d F Y à H:i:s'),
            'candidatures' => $this->getCandidatures(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDateCloture(): ?\DateTimeInterface
    {
        return $this->dateCloture;
    }

    public function setDateCloture(\DateTimeInterface $dateCloture): static
    {
        $this->dateCloture = $dateCloture;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function isIsFenced(): ?bool
    {
        return $this->isFenced;
    }

    public function setIsFenced(bool $isFenced): static
    {
        $this->isFenced = $isFenced;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): static
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setFormation($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getFormation() === $this) {
                $candidature->setFormation(null);
            }
        }

        return $this;
    }
}
