<?php

namespace App\Entity;

use App\Repository\TableauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableauRepository::class)]
class Tableau
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $codetableau = null;

    #[ORM\Column(length: 50)]
    private ?string $titretableau = null;

    #[ORM\OneToMany(targetEntity: Colonne::class, mappedBy: 'tableau', orphanRemoval: true)]
    private Collection $colonnes;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'tableau')]
    private Collection $users;

    public function __construct()
    {
        $this->colonnes = new ArrayCollection();
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

    public function getCodetableau(): ?string
    {
        return $this->codetableau;
    }

    public function setCodetableau(string $codetableau): static
    {
        $this->codetableau = $codetableau;

        return $this;
    }

    public function getTitretableau(): ?string
    {
        return $this->titretableau;
    }

    public function setTitretableau(string $titretableau): static
    {
        $this->titretableau = $titretableau;

        return $this;
    }

    /**
     * @return Collection<int, Colonne>
     */
    public function getColonnes(): Collection
    {
        return $this->colonnes;
    }

    public function addColonne(Colonne $colonne): static
    {
        if (!$this->colonnes->contains($colonne)) {
            $this->colonnes->add($colonne);
            $colonne->setTableau($this);
        }

        return $this;
    }

    public function removeColonne(Colonne $colonne): static
    {
        if ($this->colonnes->removeElement($colonne)) {
            // set the owning side to null (unless already changed)
            if ($colonne->getTableau() === $this) {
                $colonne->setTableau(null);
            }
        }

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
            $user->addTableau($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeTableau($this);
        }

        return $this;
    }
}
