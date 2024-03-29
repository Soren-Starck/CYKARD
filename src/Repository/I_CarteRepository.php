<?php

namespace App\Repository;

use App\Entity\Carte;

interface I_CarteRepository
{
    public function find(int $id): array;

    public function create(string $titre, string|null $descriptif, string|null $couleur, int $colonne_id): ?Carte;

    public function verifyUserTableauByCard(int $id, ?string $login): bool;

    public function verifyUserTableauByCardAndAccess(int $id, ?string $login): bool;

    public function updateCardWithAssign(int $id, array $data, string $login): bool;

    public function assignCard(int $idCard, ?string $login): array;

    public function unassignCard(int $id, ?string $login): bool;

    public function delete(int $id): bool;
}