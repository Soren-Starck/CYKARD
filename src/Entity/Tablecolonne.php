<?php

namespace App\Entity;

use App\Repository\TablecolonneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TablecolonneRepository::class)]
#[ORM\UniqueConstraint(name: 'tablecolonne_pk', columns: ['idtableau', 'idcolonne'])]
class Tablecolonne
{

    #[ORM\Id]
    #[ORM\Column]
    private ?int $idtableau = null;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $idcolonne = null;


    public function getIdtableau(): ?int
    {
        return $this->idtableau;
    }

    public function setIdtableau(int $idtableau): static
    {
        $this->idtableau = $idtableau;

        return $this;
    }

    public function getIdcolonne(): ?int
    {
        return $this->idcolonne;
    }

    public function setIdcolonne(int $idcolonne): static
    {
        $this->idcolonne = $idcolonne;

        return $this;
    }
}
