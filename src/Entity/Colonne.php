<?php

namespace App\Entity;

use App\Repository\ColonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColonneRepository::class)]
class Colonne
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titrecolonne = null;

    #[ORM\ManyToOne(inversedBy: 'colonnes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tableau $tableau = null;

    #[ORM\OneToMany(targetEntity: Carte::class, mappedBy: 'colonne', orphanRemoval: true)]
    private Collection $cartes;

    public function __construct()
    {
        $this->cartes = new ArrayCollection();
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

    public function getTitrecolonne(): ?string
    {
        return $this->titrecolonne;
    }

    public function setTitrecolonne(string $titrecolonne): static
    {
        $this->titrecolonne = $titrecolonne;

        return $this;
    }

    public function getTableau(): ?Tableau
    {
        return $this->tableau;
    }

    public function setTableau(?Tableau $tableau): static
    {
        $this->tableau = $tableau;

        return $this;
    }

    /**
     * @return Collection<int, Carte>
     */
    public function getCartes(): Collection
    {
        return $this->cartes;
    }

    public function addCarte(Carte $carte): static
    {
        if (!$this->cartes->contains($carte)) {
            $this->cartes->add($carte);
            $carte->setColonne($this);
        }

        return $this;
    }

    public function removeCarte(Carte $carte): static
    {
        if ($this->cartes->removeElement($carte)) {
            // set the owning side to null (unless already changed)
            if ($carte->getColonne() === $this) {
                $carte->setColonne(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titrecolonne' => $this->titrecolonne,
        ];
    }
}
