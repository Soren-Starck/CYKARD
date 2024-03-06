<?php

namespace App\Entity;

use App\Repository\TableauaffectationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableauaffectationRepository::class)]
#[ORM\UniqueConstraint(name: 'tableauaffectation_pk', columns: ['idtableau', 'login'])]
class Tableauaffectation
{

    #[ORM\Id]
    #[ORM\Column]
    private ?int $idtableau = null;

    #[ORM\Id]
    #[ORM\Column(length: 30)]
    private ?string $login = null;

    public function getIdtableau(): ?int
    {
        return $this->idtableau;
    }

    public function setIdtableau(int $idtableau): static
    {
        $this->idtableau = $idtableau;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }
}
