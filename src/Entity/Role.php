<?php

namespace App\Entity;

use JsonSerializable;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RoleRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['Role:read']],
    denormalizationContext: ['groups' => ['Role:write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Patch(),
        new Post(),
        new Delete()
    ]
)]
class Role implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Role:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Role:read', 'Role:write'])]
    private ?string $nomRole = null;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: User::class, orphanRemoval: true)]
    #[Groups(['Role:read'])]
    private Collection $users;
    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "nomRole" => $this->getNomRole(),
            "Users" => $this->getUsers()
        ];
    }
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomRole(): ?string
    {
        return $this->nomRole;
    }

    public function setNomRole(string $nomRole): static
    {
        $this->nomRole = $nomRole;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setRoles($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getRole() === $this) {
                $user->setRoles(null);
            }
        }

        return $this;
    }
    public function toArray()
    {
        $role = $this->getNomRole();
        return [$role];
    }
}
