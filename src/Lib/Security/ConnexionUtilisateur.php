<?php

namespace App\Lib\Security;

use App\Lib\Database\Database;
use App\Lib\HTTP\Session;

class ConnexionUtilisateur
{
    private static string $cleConnexion = "_utilisateurConnecte";

    public static function connecter(string $loginUtilisateur): string
    {
        $session = Session::getInstance();
        $session->enregistrer(ConnexionUtilisateur::$cleConnexion, $loginUtilisateur);
        return JsonWebToken::encoder(['login' => $loginUtilisateur]);
    }

    public static function deconnecter(): void
    {
        $session = Session::getInstance();
        $session->supprimer(ConnexionUtilisateur::$cleConnexion);
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
        $user = Database::getInstance()->table('user')
            ->where('login', '=', $login)
            ->fetchAll();
        if (empty($user)) return false;
        return MotDePasse::verifier($password, $user[0]['password']);
    }
}
