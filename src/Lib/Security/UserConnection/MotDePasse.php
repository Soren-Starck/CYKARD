<?php

namespace App\Lib\Security\UserConnection;

class MotDePasse
{

    public static function hacher(string $mdpClair): string
    {
        return password_hash($mdpClair, PASSWORD_DEFAULT);
    }

    public static function verifier(string $mdpClair, string $mdpHache): bool
    {
        return password_verify($mdpClair, $mdpHache);
    }

    public static function genererChaineAleatoire(int $nbCaracteres = 22): string
    {
        return substr(bin2hex(random_bytes(ceil($nbCaracteres * 6 / 8))), 0, $nbCaracteres);
    }
}