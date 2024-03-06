<?php

namespace App\Entity;

use App\Repository\AppDbRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppDbRepository::class)]
class AppDb
{

    #[ORM\Id]
    #[ORM\Column(length: 30)]
    private ?string $login = null;

    #[ORM\Column(length: 30)]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $mdphache = null;

    #[ORM\Column(length: 50)]
    private ?string $mdp = null;

    #[ORM\Column]
    private ?int $idtableau = null;

    #[ORM\Column(length: 255)]
    private ?string $codetableau = null;

    #[ORM\Column(length: 50)]
    private ?string $titretableau = null;

    #[ORM\Column]
    private array $participants = [];

    #[ORM\Column]
    private ?int $idcolonne = null;

    #[ORM\Column(length: 50)]
    private ?string $titrecolonne = null;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $idcarte = null;

    #[ORM\Column(length: 50)]
    private ?string $titrecarte = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptifcarte = null;

    #[ORM\Column(length: 7)]
    private ?string $couleurcarte = null;

    #[ORM\Column]
    private array $affectationscarte = [];

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMdphache(): ?string
    {
        return $this->mdphache;
    }

    public function setMdphache(string $mdphache): static
    {
        $this->mdphache = $mdphache;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

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

    public function getParticipants(): array
    {
        return $this->participants;
    }

    public function setParticipants(array $participants): static
    {
        $this->participants = $participants;

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

    public function getTitrecolonne(): ?string
    {
        return $this->titrecolonne;
    }

    public function setTitrecolonne(string $titrecolonne): static
    {
        $this->titrecolonne = $titrecolonne;

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

    public function setDescriptifcarte(string $descriptifcarte): static
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

    public function getAffectationscarte(): array
    {
        return $this->affectationscarte;
    }

    public function setAffectationscarte(array $affectationscarte): static
    {
        $this->affectationscarte = $affectationscarte;

        return $this;
    }
}
