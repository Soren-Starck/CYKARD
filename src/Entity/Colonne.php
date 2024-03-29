<?php

namespace App\Entity;

use ArrayObject;

class Colonne
{

    private ?int $id = null;

    private ?string $titrecolonne = null;

    private ?Tableau $tableau = null;

    private ArrayObject $cartes;

    public function __construct()
    {
        $this->cartes = new ArrayObject();
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
     * @return ArrayObject<int, Carte>
     */
    public function getCartes(): ArrayObject
    {
        return $this->cartes;
    }

    public function addCarte(Carte $carte): static
    {
        if (!in_array($carte, (array) $this->cartes, true)) {
            $this->cartes->append($carte);
            $carte->setColonne($this);
        }

        return $this;
    }

    public function removeCarte(Carte $carte): static
    {
        $index = array_search($carte, (array) $this->cartes, true);
        if (false !== $index) {
            $this->cartes->offsetUnset($index);
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
