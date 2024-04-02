<?php

namespace App\Lib\Route;

class Conteneur
{
    private static array $listeServices;

    public static function addService(string $nom, $service) : void {
        Conteneur::$listeServices[$nom] = $service;
    }

    public static function getService(string $nom) {
        return Conteneur::$listeServices[$nom];
    }

}
