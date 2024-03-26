<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Repository\CarteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: CarteRepository::class)]
class Carte
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titrecarte = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptifcarte = null;

    #[ORM\Column(length: 7)]
    private ?string $couleurcarte = null;

    #[ORM\ManyToOne(inversedBy: 'cartes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Colonne $colonne = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'carte')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitrecarte(): ?string
    {
        return $this->titrecarte;
    }

    public function setTitrecarte(string $titrecarte): static
    {
        $this->titrecarte = $titrecarte;

        return $this;
    }

    public function getDescriptifcarte(): ?string
    {
        return $this->descriptifcarte;
    }

    public function setDescriptifcarte(?string $descriptifcarte): static
    {
        $this->descriptifcarte = $descriptifcarte;

        return $this;
    }

    public function getCouleurcarte(): ?string
    {
        return $this->couleurcarte;
    }

    public function setCouleurcarte(string $couleurcarte): static
    {
        $this->couleurcarte = $couleurcarte;

        return $this;
    }

    public function getColonne(): ?Colonne
    {
        return $this->colonne;
    }

    public function setColonne(?Colonne $colonne): static
    {
        $this->colonne = $colonne;

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
            $user->addCarte($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeCarte($this);
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titrecarte' => $this->titrecarte,
            'descriptifcarte' => $this->descriptifcarte,
            'couleurcarte' => $this->couleurcarte,
            'colonne_id' => $this->colonne->getId(),
        ];
    }

    public function setColonneId(int $colonne_id): void
    {
        $this->colonne = new Colonne();
        $this->colonne->setId($colonne_id);
    }

    public function setUserLogin(string|null $userLogin): void
    {
        $this->userLogin = $userLogin;
    }

    public function getUserLogin(): string|null
    {
        return $this->userLogin;
    }
}
