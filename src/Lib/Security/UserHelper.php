<?php

namespace App\Lib\Security;

use App\Lib\HTTP\Session;

class UserHelper
{
    public static function isUserLoggedIn(): bool
    {
        $session = Session::getInstance();
        return $session->contient(ConnexionUtilisateur::$cleConnexion);
    }

    public static function doesUserHaveRole(string $role): bool
    {
        $roles = ConnexionUtilisateur::getRoles();
        return in_array($role, $roles[0]['roles']);
    }

    public static function getLoginUtilisateurConnecte()
    {
        $session = Session::getInstance();
        return $session->recuperer(ConnexionUtilisateur::$cleConnexion);
    }
}
