<?php

namespace App\Entity;

use App\Repository\CarteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarteRepository::class)]
class Carte
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idcarte = null;

    #[ORM\Column(length: 50)]
    private ?string $titrecarte = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptifcarte = null;

    #[ORM\Column(length: 7)]
    private ?string $couleurcarte = null;

    #[ORM\Column]
    private ?int $idcolonne = null;

    public function getIdcarte(): ?int
    {
        return $this->idcarte;
    }

    public function setIdcarte(int $idcarte): static
    {
        $this->idcarte = $idcarte;

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
