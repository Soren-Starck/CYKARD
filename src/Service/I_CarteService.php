<?php

namespace App\Service;

interface I_CarteService
{
    public function modifyCarte(mixed $data, string $login, int $id): array;

    public function showCarte(string $login, int $id): array;

    public function deleteCarte(string $login, int $id): array;

    public function createCarte(mixed $data, string $login, int $colonne_id): array;
}