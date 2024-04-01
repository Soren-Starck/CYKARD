<?php

namespace App\Lib\Route;

class Conteneur
{
    private static array $listeServices;

    public static function addService(string $nom, $service) : void {
        error_log("Adding service: $nom");
        self::$listeServices[$nom] = $service;
    }

    public static function getService(string $nom) {
        return self::$listeServices[$nom];
    }

    public function has(string $string)
    {
        return isset(self::$listeServices[$string]);
    }

    public function getServiceIds()
    {
        return array_keys(self::$listeServices);
    }

}

?>