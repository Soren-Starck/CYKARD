<?php

namespace App\Lib\HTTP;

class Cookie
{
    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void
    {
        $valeurJSON = json_encode($valeur);
        $hostname = preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST']);
        $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

        $options = [
            'path' => '/',
            'domain' => $hostname,
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Strict'
        ];

        if ($dureeExpiration !== null) {
            $options['expires'] = time() + $dureeExpiration;
        }

        setcookie($cle, $valeurJSON, $options);
    }

    public static function lire(string $cle): mixed
    {
        if (self::contient($cle)) {
            return json_decode($_COOKIE[$cle], true);
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
