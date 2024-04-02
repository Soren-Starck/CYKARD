<?php

namespace App\Repository;

use App\Entity\Carte;
use App\Entity\Colonne;
use App\Entity\Tableau;
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

    public function join(string $codetableau, string $login): false|array
    {
        try {
            $tableau = $this->db->table('tableau')->select('tableau', ['id', 'titretableau', 'codetableau'])->where('codetableau', '=', 'codetableau')->bind('codetableau', $codetableau)->fetchAll();
            $this->db->insert('user_tableau', ['user_login' => $login, 'tableau_id' => $tableau[0]['id'], 'user_role' => 'USER_READ']);
            return $tableau;
        } catch (\Exception $e) {
            return false;
        }
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

    public function verifyUserTableauAccess(string $login, mixed $id): array
    {
        return $this->db
            ->table('user_tableau')->select('user_tableau', ['user_role'])
            ->where('user_login', '=', 'login')
            ->where('tableau_id', '=', 'id')
            ->where('user_role', '!=', 'role')
            ->bind('login', $login)
            ->bind('id', $id)
            ->bind('role', 'USER_READ')
            ->fetchAll();
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
        return ($this->db
            ->table('tableau')
            ->select('tableau', [
                'tableau.id',
                'tableau.codetableau',
                'tableau.titretableau',
                'colonne.id as colonne_id',
                'colonne.titrecolonne',
                'carte.id as carte_id',
                'carte.titrecarte',
                'carte.descriptifcarte',
                'carte.couleurcarte',
                'user_carte.user_login as user_carte_login',
                'user_tableau.user_login',
                'user_tableau.user_role'
            ])
            ->leftJoin('user_tableau', 'tableau.id = user_tableau.tableau_id')
            ->leftJoin('colonne', 'tableau.id = colonne.tableau_id')
            ->leftJoin('carte', 'colonne.id = carte.colonne_id')
            ->leftJoin('user_carte', 'carte.id = user_carte.carte_id')
            ->where('user_tableau.user_login', '=', 'userLogin')
            ->where('tableau.id', '=', 'tableauId')
            ->bind('userLogin', $login)
            ->bind('tableauId', $id)
            ->union($this->db
                ->table('user_tableau')
                ->select('user_tableau', [
                    'NULL',
                    'NULL',
                    'NULL',
                    'NULL',
                    'NULL',
                    'NULL',
                    'NULL',
                    'NULL',
                    'NULL',
                    'NULL',
                    'user_login',
                    'user_role'
                ])
                ->where('tableau_id', '=', 'tableauId')
                ->bind('tableauId', $id)
                ->getQuery())
            ->fetchAll());
    }

    public function findById(string $login, int $id): array
    {
        return $this->db
            ->table('tableau')
            ->select('tableau', [
                'tableau.id',
                'tableau.titretableau',
                'user_tableau.user_login',
                'user_tableau.user_role'
            ])
            ->leftJoin('user_tableau', 'tableau.id = user_tableau.tableau_id')
            ->where('user_tableau.user_login', '=', 'userLogin')
            ->where('tableau.id', '=', 'tableauId')
            ->bind('userLogin', $login)
            ->bind('tableauId', $id)
            ->fetchAll();
    }

    public function createTableauFromDbResponse(array $dbResponse): Tableau
    {
        $index = 0;
        foreach ($dbResponse as $key => $value) {
            if ($value['id'] !== null) {
                $index = $key;
                break;
            }
        }
        $tableau = new Tableau();
        $tableau->setId($dbResponse[$index]['id']);
        $tableau->setCodetableau($dbResponse[$index]['codetableau']);
        $tableau->setTitretableau($dbResponse[$index]['titretableau']);

        $colonnes = [];
        foreach ($dbResponse as $row) {
            if (array_key_exists('colonne_id', $row) && $row['colonne_id'] !== null) {
                $colonne = new Colonne();
                $colonne->setId($row['colonne_id']);
                $colonne->setTitrecolonne($row['titrecolonne']);
                $tableau->addColonne($colonne);
                $colonnes[$row['colonne_id']] = $colonne;
                $colonne->setTableau($tableau);
            }

            if (array_key_exists('carte_id', $row) && $row['carte_id'] !== null) {
                $carte = new Carte();
                $carte->setId($row['carte_id']);
                $carte->setTitrecarte($row['titrecarte']);
                $carte->setDescriptifcarte($row['descriptifcarte']);
                $carte->setCouleurcarte($row['couleurcarte']);
                $carte->setColonne($colonnes[$row['colonne_id']]);
                $carte->setUserLogin($row['user_carte_login']);
                $colonnes[$row['colonne_id']]->addCarte($carte);
            }

            if (array_key_exists('user_login', $row) && array_key_exists('user_role', $row) && $row['user_login'] !== null && $row['user_role'] !== null) {
                $tableau->addUser($row['user_login'], $row['user_role']);
            }

        }
        return $tableau;
    }

    public function editUsersTableau(int $id, mixed $userslogins): bool
    {
        try {
            foreach ($userslogins as $login) {
                $this->db->delete('user_tableau', ['tableau_id' => $id, 'user_login' => $login]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
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

    public function editUserRoleTableau(int $id, array $userrole): bool
    {
        try {
            foreach ($userrole as $login => $role) {
                $this->db->update('user_tableau', ['user_role' => $role], ['tableau_id' => $id, 'user_login' => $login]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}