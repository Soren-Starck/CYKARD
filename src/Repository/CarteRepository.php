<?php

namespace App\Repository;

use App\Lib\Database\Database;


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

    public function updateCard(int $id, string $titrecarte, string $descriptifcarte, string $couleurcarte, int $colonne_id): void
    {
        $this->db->update('carte', [
            'titrecarte' => $titrecarte,
            'descriptifcarte' => $descriptifcarte,
            'couleurcarte' => $couleurcarte,
            'colonne_id' => $colonne_id,
        ], ['id' => $id]);
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

    public function deleteCard(int $id)
    {
        $this->db->delete('carte', ['id' => $id]);
    }

    public function createCard(mixed $titre, mixed $descriptif, mixed $couleur, mixed $colonne_id, ?string $login): array
    {
        $this->db->insert('carte', [
            'titrecarte' => $titre,
            'descriptifcarte' => $descriptif,
            'couleurcarte' => $couleur,
            'colonne_id' => $colonne_id,
        ]);
        return $this->find($this->db->lastInsertId());
    }

    public function assignCard(int $idCard, ?string $login): void
    {
        $this->db->insert('user_carte', [
            'carte_id' => $idCard,
            'user_login' => $login,
        ]);
    }

    public function unassignCard(int $id, ?string $login): void
    {
        $this->db->delete('user_carte', [
            'carte_id' => $id,
            'user_login' => $login,
        ]);
    }

    public function deleteAssigns(int $id, ?string $login): void
    {
        $this->db->delete('user_carte', [
            'carte_id' => $id,
        ]);
    }

    public function getAll(): array
    {
        return $this->db
            ->table('carte')
            ->fetchAll();
    }
}
