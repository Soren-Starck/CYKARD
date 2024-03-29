<?php

namespace App\Entity;

use ArrayObject;

class User
{

    private ?string $login = null;

    /**
     * @var list<string> The user roles
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    private ?string $password = null;

    private ?string $nom = null;

    private ?string $prenom = null;

    private ?string $email = null;

    private bool $isVerified = false;

    private ?string $verificationToken = null;

    private ArrayObject $carte;

    private ArrayObject $tableau;

    private ?string $apiToken = null;

    public function __construct()
    {
        $this->carte = new ArrayObject();
        $this->tableau = new ArrayObject();
    }

    public function getLogin(): string
    {
        return (string)$this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->login;
    }

    /**
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        if ($this->isVerified) $roles[] = 'ROLE_VERIFIED';
        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    /**
     * @return ArrayObject<int, Carte>
     */
    public function getCarte(): ArrayObject
    {
        return $this->carte;
    }

    public function addCarte(Carte $carte): static
    {
        if (!in_array($carte, (array)$this->carte, true)) {
            $this->carte->append($carte);
        }

        return $this;
    }

    public function removeCarte(Carte $carte): static
    {
        $index = array_search($carte, (array)$this->carte, true);
        if (false !== $index) {
            $this->carte->offsetUnset($index);
        }

        return $this;
    }

    /**
     * @return ArrayObject<int, Tableau>
     */
    public function getTableau(): ArrayObject
    {
        return $this->tableau;
    }

    public function addTableau(Tableau $tableau): static
    {
        if (!in_array($tableau, (array)$this->tableau, true)) {
            $this->tableau->append($tableau);
        }

        return $this;
    }

    public function removeTableau(Tableau $tableau): static
    {
        $index = array_search($tableau, (array)$this->tableau, true);
        if (false !== $index) {
            $this->tableau->offsetUnset($index);
        }

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): static
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function getVerificationToken(): ?string
    {
        return $this->verificationToken;
    }

    public function setVerificationToken(?string $verificationToken): self
    {
        $this->verificationToken = $verificationToken;
        return $this;
    }
}
