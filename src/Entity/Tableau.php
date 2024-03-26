<?php

namespace App\Entity;

use App\Repository\TableauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TableauRepository::class)]
class Tableau implements \JsonSerializable
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tableau.index'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tableau.index'])]
    private ?string $codetableau = null;

    #[ORM\Column(length: 50)]
    #[Groups(['tableau.show'])]
    private ?string $titretableau = null;

    #[ORM\OneToMany(targetEntity: Colonne::class, mappedBy: 'tableau', orphanRemoval: true)]
    private Collection $colonnes;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'tableau')]
    private Collection $users;

    public function __construct()
    {
        $this->colonnes = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection<int, Colonne>
     */
    public function getColonnes(): Collection
    {
        return $this->colonnes;
    }

    public function addColonne(Colonne $colonne): static
    {
        if (!$this->colonnes->contains($colonne)) {
            $this->colonnes->add($colonne);
            $colonne->setTableau($this);
        }

        return $this;
    }

    public function removeColonne(Colonne $colonne): static
    {
        if ($this->colonnes->removeElement($colonne)) {
            // set the owning side to null (unless already changed)
            if ($colonne->getTableau() === $this) {
                $colonne->setTableau(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(string $user_login, string $user_role): static
    {
        $user = new User();
        $user->setLogin($user_login);
        $user->setRoles([$user_role]);
        $this->users->add($user);
        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeTableau($this);
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
            $cartes = [];
            foreach ($colonne->getCartes() as $carte) {
                $cartes[] = [
                    'id' => $carte->getId(),
                    'titrecarte' => $carte->getTitrecarte(),
                    'descriptifcarte' => $carte->getDescriptifcarte(),
                    'couleurcarte' => $carte->getCouleurcarte(),
                    'colonne_id' => $carte->getColonne()->getId(),
                ];
            }

            $colonnes[] = [
                'id' => $colonne->getId(),
                'titrecolonne' => $colonne->getTitrecolonne(),
                'tableau_id' => $colonne->getTableau()->getId(),
                'cartes' => $cartes,
            ];
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
            'colonnes' => $colonnes,
            'users' => $users,
        ];
    }

}
