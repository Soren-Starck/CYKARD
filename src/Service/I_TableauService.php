<?php

namespace App\Service;

interface I_TableauService
{
    public function modifyTableau(mixed $data, string $login, int $id): array;

    public function modifyName(string $titre, string $login, int $id): array;

    public function addUser(string $user, string $login, int $id): array;

    public function modifyRole(string $user, string $role, string $login, int $id): array;

    public function deleteUser(string $user, string $login, int $id): array;

    public function showTableau(string $login, int $id): array;

    public function deleteTableau(string $login, int $id): array;

    public function createTableau(mixed $data, string $login): array;

    public function joinTableau(string $login, string $codetableau): array;

    public function getTableaux(string $login, array $roles): array;
}