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

    public function findTableauColonnes(string $login, mixed $id): array
    {
        return $this->db
            ->table('tableau')
            ->leftJoin('user_tableau', 'tableau.id = user_tableau.tableau_id')
            ->leftJoin('colonne', 'tableau.id = colonne.tableau_id')
            ->leftJoin('carte', 'colonne.id = carte.colonne_id')
            ->where('user_tableau.user_login', '=', 'userLogin')
            ->where('tableau.id', '=', 'tableauId')
            ->bind('userLogin', $login)
            ->bind('tableauId', $id)
            ->fetchAll();
    }

    public function findAll(): array
    {
        return $this->db
            ->table('gozzog.tableau')
            ->fetchAll();
    }

    public function modify($id, $titre)
    {
        $this->db->update(
            'gozzog.tableau',
            ['titretableau' => $titre],
            ['id' => $id]
        );
    }

    public function verifyUserTableau(string $login, mixed $id): bool
    {
        return $this->db
            ->table('user_tableau')
            ->where('user_login', '=', 'login')
            ->where('tableau_id', '=', 'id')
            ->bind('login', $login)
            ->bind('id', $id)
            ->fetchAll() !== [];
    }

    public function create(mixed $titre, ?string $login)
    {
        $this->db->insert(
            'gozzog.tableau',
            ['titretableau' => $titre]
        );
        $id = $this->db->lastInsertId();
        $this->db->insert(
            'gozzog.user_tableau',
            ['user_login' => $login, 'tableau_id' => $id]
        );
        return $id;
    }

}
