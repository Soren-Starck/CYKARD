<?php

namespace App\Repository;

use App\Entity\User;

interface I_UserRepository extends AbstractRepository
{
    public function getUserByLogin(string $login): array;

    public function getUserRoles(string $login): array;

    public function createUser(User $user): void;

    public static function getRoles(): array;

    public function verifyUser(string $token): void;

    public function verifierCredentials(string $login, string $password): bool;
}
