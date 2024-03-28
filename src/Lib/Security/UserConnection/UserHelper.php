<?php

namespace App\Lib\Security\UserConnection;

use App\Lib\HTTP\Session;
use App\Repository\UserRepository;

class UserHelper
{
    public static function isUserLoggedIn(): bool
    {
        $session = Session::getInstance();
        return $session->contient(ConnexionUtilisateur::$cleConnexion);
    }

    public static function doesUserHaveRole(string $role): bool
    {
        $roles = UserRepository::getRoles();
        return in_array($role, $roles[0]['roles']);
    }

    public static function getLoginUtilisateurConnecte()
    {
        $session = Session::getInstance();
        return $session->recuperer(ConnexionUtilisateur::$cleConnexion);
    }
}
