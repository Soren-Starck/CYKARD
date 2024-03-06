<?php

namespace App\Entity;

use App\Repository\ColonneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColonneRepository::class)]
class Colonne
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idcolonne = null;

    #[ORM\Column(length: 50)]
    private ?string $titrecolonne = null;

    public function getIdcolonne(): ?int
    {
        return $this->idcolonne;
    }

    public function setIdcolonne(int $idcolonne): static
    {
        $this->idcolonne = $idcolonne;

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
}
