<?php

namespace App\Repository;

use App\Entity\Colonne;
use App\Lib\Database\Database;

class ColonneRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findByTableau(string $login, $id): array
    {
        return $this->db
            ->table('colonne')->select('colonne', ['colonne.id', 'titrecolonne'])
            ->leftJoin('tableau', 'colonne.tableau_id = tableau.id')
            ->join('user_tableau', 'tableau.id = user_tableau.tableau_id')
            ->where('user_tableau.user_login', '=', 'userLogin')
            ->where('tableau.id', '=', 'tableauId')
            ->bind('userLogin', $login)
            ->bind('tableauId', $id)
            ->fetchAll();
    }

    public function findByTableauAndColonne(string $login, $id): array
    {
        return $this->db
            ->table('colonne')->select('colonne', ['colonne.id', 'titrecolonne'])
            ->leftJoin('tableau', 'colonne.tableau_id = tableau.id')
            ->join('user_tableau', 'tableau.id = user_tableau.tableau_id')
            ->where('user_tableau.user_login', '=', 'userLogin')
            ->where('colonne.id', '=', 'colonneId')
            ->bind('userLogin', $login)
            ->bind('colonneId', $id)
            ->fetchAll();
    }

    public function editTitreColonne($id, mixed $titre): bool
    {
        try {
            $this->db->update('colonne', ['titrecolonne' => $titre], ['id' => $id]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function verifyUserTableauByColonne(?string $login, $id): bool
    {
        return $this->db
                ->table('colonne')
                ->leftJoin('tableau', 'colonne.tableau_id = tableau.id')
                ->join('user_tableau', 'tableau.id = user_tableau.tableau_id')
                ->where('user_tableau.user_login', '=', 'userLogin')
                ->where('colonne.id', '=', 'colonneId')
                ->where('user_tableau.user_role', '!=', 'role')
                ->bind('userLogin', $login)
                ->bind('colonneId', $id)
                ->bind('role', 'USER_READ')
                ->fetchAll() !== [];
    }

    public function delete($id): bool
    {
        try {
            $this->db->delete('colonne', ['id' => $id]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function create(string $titre, int $tableau_id): ?Colonne
    {
        try {
            $this->db->insert('colonne', ['titrecolonne' => $titre, 'tableau_id' => $tableau_id]);
            $colonne = new Colonne();
            $colonne->setId($this->db->lastInsertId());
            $colonne->setTitreColonne($titre);
            return $colonne;
        } catch (\Exception $e) {
            return null;
        }
    }
}
