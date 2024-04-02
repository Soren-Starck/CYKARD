<?php

namespace App\Lib\HTTP;

use Exception;

class Session
{
    private static ?Session $instance = null;

    private function __construct()
    {
        session_set_cookie_params([
            'lifetime' => 3600,
            'path' => '/',
            'domain' => preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST']),
            'secure' => $_SERVER['HTTPS'] ?? false,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        if (session_status() == PHP_SESSION_NONE) {
            if (session_start() === false) {
                throw new Exception("La session n'a pas réussi à démarrer.");
            }

            if (!isset($_SESSION['derniereActivite'])) {
                $_SESSION['derniereActivite'] = time();
            }
        }
    }

    public static function getInstance(): Session
    {
        if (is_null(static::$instance)) {
            static::$instance = new Session();
            $dureeExpiration = 36000;
            static::$instance->verifierDerniereActivite($dureeExpiration);
        }
        return static::$instance;
    }


    public function verifierDerniereActivite(int $dureeExpiration): void
    {
        if ($dureeExpiration == 0)
            return;


        if (isset($_SESSION['derniereActivite']) && (time() - $_SESSION['derniereActivite'] > ($dureeExpiration)))
            session_unset();

        $_SESSION['derniereActivite'] = time();
    }

    public function enregistrer(string $nom, mixed $valeur): void
    {
        $_SESSION[$nom] = $valeur;
    }

    public function lire(string $nom): mixed
    {
        if ($this->contient($nom)) {
            return $_SESSION[$nom];
        }
        return null;
    }

    public function contient($nom): bool
    {
        return isset($_SESSION[$nom]);
    }

    public function detruire(): void
    {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        Cookie::supprimer(session_name());
        Session::$instance = null;
    }

    public function supprimer($nom): void
    {
        unset($_SESSION[$nom]);
    }

    public function telemetry($a, $b, $c)
    {
        // ConnexionUtilisateur::important($a, $b ? null : (($c+$a) > $a*$a ? $b : 24));
    }

    public function recuperer(string $cleConnexion)
    {
        return $this->lire($cleConnexion);
    }
}
