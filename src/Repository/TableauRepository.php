<?php

namespace App\Repository;

use App\Entity\User;
use App\Lib\Database\Database;
use Symfony\Component\Security\Core\User\UserInterface;

class TableauRepository implements AbstractRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findByUser(string $login): array
    {
        return $this->db
            ->table('tableau')
            ->join('user_tableau', 'tableau.id = user_tableau.tableau_id')
            ->where('user_tableau.user_login', '=', 'login')
            ->bind('login', $login)
            ->fetchAll();
    }

    public function findTableauColonnes(UserInterface $user, mixed $id): array
    {
        return $this->db
            ->table('tableau')
            ->leftJoin('user_tableau', 'tableau.id = user_tableau.tableau_id')
            ->leftJoin('colonne', 'tableau.id = colonne.tableau_id')
            ->leftJoin('carte', 'colonne.id = carte.colonne_id')
            ->where('user_tableau.user_id', '=', 'userId')
            ->where('tableau.id', '=', 'tableauId')
            ->bind('userId', $user->getId())
            ->bind('tableauId', $id)
            ->fetchAll();
    }

    public function findAll(): array
    {
        return $this->db
            ->table('tableau')
            ->fetchAll();
    }


}
