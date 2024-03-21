<?php

namespace App\Repository;

use App\Lib\Database\Database;

class ColonneRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function verifyUserTableauByColonne(int $id_colonne, string $login)
    {
        return $this->db
            ->table('colonne')
            ->leftJoin('tableau', 'colonnes.tableau_id = tableau.id')
            ->join('user_tableau', 'tableau.id = user_tableau.tableau_id')
            ->where('colonne_id', '=', 'id_colonne')
            ->where('user_login', '=', 'login')
            ->bind('id_colonne', $id_colonne)
            ->bind('login', $login)
            ->fetchAll();
    }

}
