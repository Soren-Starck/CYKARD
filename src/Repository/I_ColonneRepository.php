<?php

namespace App\Repository;

use App\Entity\Colonne;

interface I_ColonneRepository
{
    public function findByTableau(string $login, $id): array;

    public function findByTableauAndColonne(string $login, $id): array;

    public function editTitreColonne($id, mixed $titre): bool;

    public function verifyUserTableauByColonne(?string $login, $id): bool;

    public function delete($id): bool;

    public function create(string $titre, int $tableau_id): Colonne|bool|null;
}
