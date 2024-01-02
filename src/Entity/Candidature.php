<?php

namespace App\Entity;

use JsonSerializable;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\FormationController;
use App\Repository\CandidatureRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:collection', 'read:Candidature']],
    denormalizationContext: ['Groups' => ['write:Candidature']],
    operations: [
        new Post(),
        new Get(),
        new GetCollection(),
        new Get(
            name: 'Liste des candidatures acceptees',
            uriTemplate: '/accepted-candidatures',
            controller: FormationController::class . '::ListAcceptedCandidatures',
            description: 'Cet endpoint permet de recuperer les candidatures acceptées ',
            read: false,
            deserialize: false
        ),
        new Get(
            name: 'Liste des candidatures refusees',
            uriTemplate: '/refused-candidatures',
            controller: FormationController::class . '::ListRefusedCandidatures',
            description: 'Cet endpoint permet de recuperer les candidatures acceptées dans une formations donnée ',
            read: false,
            deserialize: false
        ),
    ]
)]
class Candidature implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:Candidature'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['write:Candidature'])]
    private ?Statut $statut = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $formation = null;

    #[ORM\Column]
    #[Groups(['read:Candidature'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "statut" => $this->getStatut(),
            "user" => $this->getUser(),
            "formation" => $this->getFormation(),
            "createdAt" => $this->getCreatedAt()->format('d F Y  H:i:s'), // Formatage de la date
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): static
    {
        $this->statut = $statut;

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

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): static
    {
        $this->formation = $formation;

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
}
