<?php

namespace App\Entity;

use JsonSerializable;
use App\Entity\Candidature;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StatutRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['Statut:read', 'read:collection']],
    denormalizationContext: ['groups' => ['Statut:write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Patch(),
        new Post(),
        new Delete()
    ]
)]
class Statut implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Statut:read',])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Statut:read', 'Statut:write'])]
    private ?string $nomStatut = null;

    #[ORM\OneToMany(mappedBy: 'statut', targetEntity: Candidature::class, orphanRemoval: true)]
    #[Groups(['read:collection'])]
    private Collection $candidatures;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'nomStatut' => $this->getNomStatut(),
            'candidatures' => $this->getCandidatures(),
        ];
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomStatut(): ?string
    {
        return $this->nomStatut;
    }

    public function setNomStatut(string $nomStatut): static
    {
        $this->nomStatut = $nomStatut;

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
            $candidature->setStatut($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getStatut() === $this) {
                $candidature->setStatut(null);
            }
        }

        return $this;
    }
}
