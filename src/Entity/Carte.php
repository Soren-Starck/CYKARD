<?php

namespace App\Entity;

use AllowDynamicProperties;
use ArrayObject;

#[AllowDynamicProperties] class Carte
{
    private ?int $id = null;

    private ?string $titrecarte = null;

    private ?string $descriptifcarte = null;

    private ?string $couleurcarte = null;

    private ?Colonne $colonne = null;

    private ArrayObject $users;

    public function __construct()
    {
        $this->users = new ArrayObject();
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

    public function getColonne(): ?Colonne
    {
        return $this->colonne;
    }

    public function setColonne(?Colonne $colonne): static
    {
        $this->colonne = $colonne;

        return $this;
    }

    /**
     * @return ArrayObject<int, User>
     */
    public function getUsers(): ArrayObject
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!in_array($user, (array) $this->users, true)) {
            $this->users->append($user);
            $user->addCarte($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $index = array_search($user, (array) $this->users, true);
        if (false !== $index) {
            $this->users->offsetUnset($index);
            $user->removeCarte($this);
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titrecarte' => $this->titrecarte,
            'descriptifcarte' => $this->descriptifcarte,
            'couleurcarte' => $this->couleurcarte,
            'colonne_id' => $this->colonne->getId(),
        ];
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

    public function setColonneId(int $colonne_id): void
    {
        $this->colonne = new Colonne();
        $this->colonne->setId($colonne_id);
    }

    public function setUserLogin(string|null $userLogin): void
    {
        $this->userLogin = $userLogin;
    }

    public function getUserLogin(): string|null
    {
        return $this->userLogin;
    }

}
