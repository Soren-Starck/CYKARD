<?php

namespace App\Repository;

use App\Lib\Database\Database;

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

    public function findAll(): array
    {
        return $this->db
            ->table('gozzog.tableau')
            ->fetchAll();
    }

    public function editTitreTableau($id, $titre): bool
    {
        try {
            $this->db->update('tableau', ['titretableau' => $titre], ['id' => $id]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
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

    public function delete(mixed $id): bool
    {
        try {
            $this->db->delete('tableau', ['id' => $id]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function create(mixed $titre, ?string $login): array|bool
    {
        try {
            $this->db->insert('tableau', ['titretableau' => $titre]);
            $tableauId = $this->db->lastInsertId();
            $this->db->insert('user_tableau', ['user_login' => $login, 'tableau_id' => $tableauId, 'user_role' => 'USER_ADMIN']);
            return $this->db->table('tableau')->select('tableau', ['id', 'titretableau', 'codetableau'])->where('id', '=', 'id')->bind('id', $tableauId)->fetchAll();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findTableauColonnes(string $login, int $id): array
    {
        return $this->db
            ->table('tableau')->select('tableau', ['tableau.id', 'tableau.codetableau', 'tableau.titretableau', 'colonne.id as colonne_id', 'colonne.titrecolonne', 'carte.id as id_carte', 'carte.titrecarte', 'carte.descriptifcarte', 'carte.couleurcarte'])
            ->leftJoin('user_tableau', 'tableau.id = user_tableau.tableau_id')
            ->leftJoin('colonne', 'tableau.id = colonne.tableau_id')
            ->leftJoin('carte', 'colonne.id = carte.colonne_id')
            ->where('user_tableau.user_login', '=', 'userLogin')
            ->where('tableau.id', '=', 'tableauId')
            ->bind('userLogin', $login)
            ->bind('tableauId', $id)
            ->fetchAll();
    }
}
