<?php

namespace App\Service;

interface I_TableauService
{
    public function modifyTableau(mixed $data, string $login, int $id): array;

    public function modifyName(mixed $data, string $login, int $id): array;

    public function addUser(mixed $data, string $login, int $id): array;

    public function modifyRole(mixed $data, string $login, int $id): array;

    public function deleteUser(string $login, int $id): array;

    public function showTableau(string $login, int $id): array;

    public function deleteTableau(string $login, int $id): array;

    public function createTableau(mixed $data, string $login): array;

    public function joinTableau(string $login, string $codetableau): array;

    public function getTableaux(string $login, array $roles): array;
}