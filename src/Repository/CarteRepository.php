<?php

namespace App\Repository;

use App\Entity\Carte;
use App\Entity\Colonne;
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

    public function findAssign(int $id): array
    {
        return $this->db
            ->table('user_carte')
            ->where('carte_id', '=', 'id')
            ->bind('id', $id)
            ->fetchAll();
    }

    public function updateCard(int $id, string $titrecarte, string $descriptifcarte, string $couleurcarte, int $colonne_id): bool
    {
           try {
                $this->db->update('carte', [
                    'titrecarte' => $titrecarte,
                    'descriptifcarte' => $descriptifcarte,
                    'couleurcarte' => $couleurcarte,
                    'colonne_id' => $colonne_id,
                ], [
                    'id' => $id,
                ]);
                return true;
            } catch (Exception $e) {
                return false;
            }
    }

    public function verifyUserCarte(int $id, ?string $login): bool
    {
        return $this->db
            ->table('user_carte')
            ->where('carte_id', '=', 'id')
            ->where('user_login', '=', 'login')
            ->bind('id', $id)
            ->bind('login', $login)
            ->fetchAll() !== [];
    }

    public function deleteCard(int $id): bool
    {
        try {
            $this->db->delete('carte', [
                'id' => $id,
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function createCard(mixed $titre, mixed $descriptif, mixed $couleur, int $colonne_id): ?Carte
    {
        try {
            $this->db->insert('carte', [
                'titrecarte' => $titre,
                'descriptifcarte' => $descriptif,
                'couleurcarte' => $couleur,
                'colonne_id' => $colonne_id,
            ]);
            $carte = new Carte();
            $carte->setId($this->db->lastInsertId());
            $carte->setTitrecarte($titre);
            $carte->setDescriptifcarte($descriptif);
            $carte->setCouleurcarte($couleur);
            return $carte;
        } catch (Exception $e) {
            return null;
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

    public function deleteAssigns(int $id): bool
    {
        try {
            $this->db->delete('user_carte', [
                'carte_id' => $id,
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getAll(): array
    {
        $array = $this->db
            ->table('carte')
            ->fetchAll();
        $cards = [];
        foreach ($array as $item) {
            $carte = new Carte();
            $carte->setId($item['id']);
            $carte->setTitrecarte($item['titrecarte']);
            $carte->setDescriptifcarte($item['descriptifcarte']);
            $carte->setCouleurcarte($item['couleurcarte']);
            $carte->setColonne($item['colonne_id']);
            $cards[] = $carte;
        }
        return $cards;
    }

    public function verifyUserColonne($id_colonne, string $login): bool
    {
        return $this->db
            ->table('colonne')
            ->where('id', '=', 'id_colonne')
            ->where('tableau_id', '=', 'tableau_id')
            ->bind('id_colonne', $id_colonne)
            ->bind('tableau_id', $login)
            ->fetchAll() !== [];
    }
}
