<?php

namespace App\Lib\Security;

use App\Lib\Database\Database;
use App\Lib\HTTP\Cookie;
use App\Lib\HTTP\Session;

class ConnexionUtilisateur
{
    public static string $cleConnexion = "_utilisateurConnecte";

    public static function connecter(string $loginUtilisateur): string
    {
        $session = Session::getInstance();
        $session->enregistrer(ConnexionUtilisateur::$cleConnexion, $loginUtilisateur);
        $jwt = JsonWebToken::encoder(['login' => $loginUtilisateur]);
        Cookie::enregistrer('jwt', $jwt);
        return $jwt;
    }

    public static function deconnecter(): void
    {
        $session = Session::getInstance();
        Cookie::supprimer('jwt');
        $session->supprimer(ConnexionUtilisateur::$cleConnexion);
        $session->detruire();
    }

    public static function estUtilisateur($login): bool
    {
        if (ConnexionUtilisateur::estConnecte()) {
            return ConnexionUtilisateur::getLoginUtilisateurConnecte() == $login;
        }
        return false;
    }

    public static function estConnecte(): bool
    {
        $session = Session::getInstance();
        return $session->contient(ConnexionUtilisateur::$cleConnexion);
    }

    public static function getLoginUtilisateurConnecte(): ?string
    {
        $session = Session::getInstance();
        if ($session->contient(ConnexionUtilisateur::$cleConnexion)) {
            return $session->lire(ConnexionUtilisateur::$cleConnexion);
        } else
            return null;
    }

    public static function verifierCredentials(string $login, string $password): bool
    {
        $user = Database::getInstance()->table('gozzog.user')
            ->where('login', '=', 'login')
            ->bind('login', $login)
            ->fetchAll();
        if (empty($user)) return false;
        return MotDePasse::verifier($password, $user[0]['password']);
    }

    public static function getRoles(): array
    {
        $roles = Database::getInstance()->table('gozzog.user')
            ->select('gozzog.user', ['roles'])
            ->where('login', '=', 'login')
            ->bind('login', self::getLoginUtilisateurConnecte())
            ->fetchAll();

        return $roles;
    }
}
