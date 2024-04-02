<?php

namespace App\Service;

use App\Entity\User;

interface I_UserService
{
    public function getUserByLogin(?string $login): array;

    public function verifierCredentials(float|bool|int|string|null $loginUtilisateur, float|bool|int|string|null $password): bool;

    public function createUser(User $user): void;

    public function verifyUser(string $token): void;

    public function modifyName(mixed $nom, string $login);

    public function modifyPrenom(mixed $prenom, string $login);

    public function modifyMail(mixed $mail, string $login);

    public function modifyPassword(mixed $old_password, mixed $new_password, string $login);

}
