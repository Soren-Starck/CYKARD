<?php

namespace App\Lib\HTTP;

class Cookie
{
    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void
    {
        $valeurJSON = serialize($valeur);
        $hostname = preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST']);
        $secure = $_SERVER['HTTPS'] ?? false;
        if ($dureeExpiration === null)
            setcookie($cle, $valeurJSON, [
                'expires' => 0,
                'path' => '/',
                'domain' => $hostname,
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        else
            setcookie($cle, $valeurJSON, [
                'expires' => time() + $dureeExpiration,
                'path' => '/',
                'domain' => $hostname,
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
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
