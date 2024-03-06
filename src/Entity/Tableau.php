<?php

namespace App\Entity;

use App\Repository\TableauRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableauRepository::class)]
class Tableau
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idtableau = null;

    #[ORM\Column(length: 255)]
    private ?string $codetableau = null;

    #[ORM\Column(length: 50)]
    private ?string $titretableau = null;

    public function getIdtableau(): ?int
    {
        return $this->idtableau;
    }

    public function setIdtableau(int $idtableau): static
    {
        $this->idtableau = $idtableau;

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
}
