<?php

namespace App\Entity;

use App\Repository\AffectationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AffectationRepository::class)]
#[ORM\UniqueConstraint(name: 'affectation_pk', columns: ['login', 'idcarte'])]

class Affectation
{

    #[ORM\Id]
    #[ORM\Column(length: 30)]
    private ?string $login = null;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $idcarte = null;


    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getIdcarte(): ?int
    {
        return $this->idcarte;
    }

    public function setIdcarte(int $idcarte): static
    {
        $this->idcarte = $idcarte;

        return $this;
    }
}
