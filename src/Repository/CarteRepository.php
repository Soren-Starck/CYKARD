<?php

namespace App\Repository;

use App\Entity\Carte;
use App\Lib\Database\Database;
use Exception;


class CarteRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function find(int $id): array
    {
        return $this->db
            ->table('carte')
            ->where('id', '=', 'id')
            ->bind('id', $id)
            ->fetchAll();
    }

    public function create(string $titre, string|null $descriptif, string|null $couleur, int $colonne_id): ?Carte
    {
        try {
            $params = [
                'titrecarte' => $titre,
                'descriptifcarte' => $descriptif,
                'couleurcarte' => $couleur,
                'colonne_id' => $colonne_id,
            ];
            $params = array_filter($params);
            $columns = implode(', ', array_keys($params));
            $values = ':' . implode(', :', array_keys($params));
            $query = "INSERT INTO carte ($columns) VALUES ($values)";
            $this->db->raw($query, $params);
            $carte = new Carte();
            $carte->setId($this->db->lastInsertId());
            $carte->setTitrecarte($titre);
            $carte->setDescriptifcarte($descriptif ?? '');
            $carte->setCouleurcarte($couleur ?? '#ffffff');
            $carte->setColonneId($colonne_id);
            return $carte;
        } catch (Exception $e) {
            return null;
        }
    }
    public function verifyUserTableauByCard(int $id, ?string $login): bool
    {
        return $this->db
                ->table('carte')
                ->leftJoin('colonne', 'carte.colonne_id = colonne.id')
                ->join('tableau', 'colonne.tableau_id = tableau.id')
                ->join('user_tableau', 'tableau.id = user_tableau.tableau_id')
                ->where('carte.id', '=', 'id')
                ->where('user_tableau.user_login', '=', 'login')
                ->bind('id', $id)
                ->bind('login', $login)
                ->fetchAll() !== [];
    }
    public function verifyUserTableauByCardAndAccess(int $id, ?string $login): bool
    {
        return $this->db
                ->table('carte')
                ->leftJoin('colonne', 'carte.colonne_id = colonne.id')
                ->join('tableau', 'colonne.tableau_id = tableau.id')
                ->join('user_tableau', 'tableau.id = user_tableau.tableau_id')
                ->where('carte.id', '=', 'id')
                ->where('user_tableau.user_login', '=', 'login')
                ->where('user_tableau.user_role', '!=', 'role')
                ->bind('id', $id)
                ->bind('login', $login)
                ->bind('role', 'USER_READ')
                ->fetchAll() !== [];
    }
    public function updateCardWithAssign(int $id, array $data, string $login): bool
    {
        try {
            $updateData = array_intersect_key($data, array_flip(['titrecarte', 'descriptifcarte', 'couleurcarte', 'colonne_id']));
            if (!empty($updateData)) $this->db->update('carte', $updateData, ['id' => $id]);
            if (isset($data['assign'])) $this->assignCard($id, $login);
            else if (isset($data['unassign'])) $this->unassignCard($id, $login);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public function assignCard(int $idCard, ?string $login): array
    {
        try {
            $this->db->insert('user_carte', [
                'carte_id' => $idCard,
                'user_login' => $login,
            ]);
            return [
                'carte_id' => $idCard,
                'user_login' => $login,
            ];
        } catch (Exception $e) {
            return [];
        }
    }
    public function unassignCard(int $id, ?string $login): bool
    {
        try {
            $this->db->delete('user_carte', [
                'carte_id' => $id,
                'user_login' => $login,
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $this->db->delete('carte', ['id' => $id,]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
