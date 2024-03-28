<?php

namespace App\Entity;

use ArrayObject;


class Tableau implements \JsonSerializable
{

    private ?int $id = null;

    private ?string $codetableau = null;
    private ?string $titretableau = null;

    private ArrayObject $colonnes;

    private ArrayObject $users;

    public function __construct()
    {
        $this->colonnes = new ArrayObject();
        $this->users = new ArrayObject();
    }

    public function addColonne(Colonne $colonne): static
    {
        if (!in_array($colonne, (array) $this->colonnes, true)) {
            $this->colonnes->append($colonne);
            $colonne->setTableau($this);
        }

        return $this;
    }

    public function removeColonne(Colonne $colonne): static
    {
        $index = array_search($colonne, (array) $this->colonnes, true);
        if (false !== $index) {
            $this->colonnes->offsetUnset($index);
            if ($colonne->getTableau() === $this) {
                $colonne->setTableau(null);
            }
        }

        return $this;
    }

    public function addUser(string $user_login, string $user_role): static
    {
        $user = new User();
        $user->setLogin($user_login);
        $user->setRoles([$user_role]);
        $this->users->append($user);
        return $this;
    }

    public function removeUser(User $user): static
    {
        $index = array_search($user, (array) $this->users, true);
        if (false !== $index) {
            $this->users->offsetUnset($index);
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'codetableau' => $this->codetableau,
            'titretableau' => $this->titretableau,
            'colonnes' => $this->colonnes,
            'users' => $this->users,
        ];
    }

    public function toArray(): array
    {
        $colonnes = [];
        foreach ($this->getColonnes() as $colonne) {
            if (!isset($colonnes[$colonne->getId()])) {
                $colonnes[$colonne->getId()] = [
                    'id' => $colonne->getId(),
                    'titrecolonne' => $colonne->getTitrecolonne(),
                    'tableau_id' => $colonne->getTableau()->getId(),
                    'cartes' => [],
                ];
            }

            foreach ($colonne->getCartes() as $carte) {
                $colonnes[$colonne->getId()]['cartes'][] = [
                    'id' => $carte->getId(),
                    'titrecarte' => $carte->getTitrecarte(),
                    'descriptifcarte' => $carte->getDescriptifcarte(),
                    'couleurcarte' => $carte->getCouleurcarte(),
                    'colonne_id' => $carte->getColonne()->getId(),
                    'user_carte_login' => $carte->getUserLogin(),
                ];
            }
        }

        $users = [];
        foreach ($this->getUsers() as $user) {
            $users[] = [
                'login' => $user->getLogin(),
                'role' => $user->getRoles()[0],
            ];
        }

        $users = array_map("unserialize", array_unique(array_map("serialize", $users)));
        $users = array_values($users);

        return [
            'id' => $this->getId(),
            'codetableau' => $this->getCodetableau(),
            'titretableau' => $this->getTitretableau(),
            'colonnes' => array_values($colonnes),
            'users' => $users,
        ];
    }

    /**
     * @return ArrayObject<int, Colonne>
     */
    public function getColonnes(): ArrayObject
    {
        return $this->colonnes;
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

    /**
     * @return ArrayObject<int, User>
     */
    public function getUsers(): ArrayObject
    {
        return $this->users;
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
