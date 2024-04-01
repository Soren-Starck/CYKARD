<?php

namespace App\Service;

interface I_ColonneService
{
    public function modifyColonne(mixed $data, string $login, int $id): array;

    public function deleteColonne(string $login, int $id): array;

    public function createColonne(mixed $data, string $login, int $tableau_id): array;
}