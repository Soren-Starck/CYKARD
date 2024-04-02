<?php

namespace App\Repository;

use App\Entity\Tableau;

interface I_TableauRepository extends AbstractRepository
{
    public function findByUser(string $login): array;
    public function join(string $codetableau, string $login): false|Tableau;

    public function editTitreTableau($id, $titre): bool;

    public function verifyUserTableau(string $login, mixed $id): bool;

    public function verifyUserTableauAccess(string $login, mixed $id): array;

    public function create(mixed $titre, ?string $login): bool|Tableau;

    public function findTableauColonnes(string $login, int $id): array;

    public function createTableauFromDbResponse(array $dbResponse): Tableau;

    public function editUsersTableau(int $id, mixed $userslogins): bool;

    public function delete(mixed $id): bool;

    public function editUserRoleTableau(int $id, string $role, string $login): bool;
}
