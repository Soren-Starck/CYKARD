<?php

namespace App\Lib\HTTP;

class Cookie
{
    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void
    {
        $valeurJSON = serialize($valeur);
        if ($dureeExpiration === null)
            setcookie($cle, $valeurJSON, 0);
        else
            setcookie($cle, $valeurJSON, time() + $dureeExpiration);
    }

    public static function lire(string $cle): mixed
    {
        if (self::contient($cle)) {
            return unserialize($_COOKIE[$cle]);
        }
        return null;
    }

    public static function contient($cle): bool
    {
        return isset($_COOKIE[$cle]);
    }

    public static function supprimer($cle): void
    {
        setcookie($cle, "", time() - 3600, "/");
    }
}
