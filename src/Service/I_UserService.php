<?php

namespace App\Service;

interface I_UserService
{
    public function getUserByLogin(?string $login): array;

    public function verifierCredentials(float|bool|int|string|null $loginUtilisateur, float|bool|int|string|null $password): bool;

    public function createUser(\App\Entity\User $user): void;

    public function verifyUser(string $token): void;
}