<?php

namespace App\Repository;

use App\Entity\User;
use App\Lib\Database\Database;
use App\Lib\Security\UserConnection\ConnexionUtilisateur;
use App\Lib\Security\UserConnection\MotDePasse;

class UserRepository implements I_UserRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getUserByLogin(string $login): array
    {
        return $this->db->table('gozzog.user')
            ->where('login', '=', 'login')
            ->bind('login', $login)
            ->fetchAll();
    }

    public function getUserRoles(string $login): array
    {
        return $this->db->table('gozzog.user')
            ->select('gozzog.user', ['roles'])
            ->where('login', '=', 'login')
            ->bind('login', $login)
            ->fetchAll();
    }

    public function createUser(User $user): void
    {
        $this->db->insert('user', [
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
            'roles' => json_encode($user->getRoles()),
            'is_verified' => 0,
            'verification_token' => $user->getVerificationToken()
        ]);
    }

    public static function getRoles(): array
    {
        return Database::getInstance()->table('gozzog.user')
            ->select('gozzog.user', ['roles'])
            ->where('login', '=', 'login')
            ->bind('login', ConnexionUtilisateur::getLoginUtilisateurConnecte())
            ->fetchAll();
    }

    public function verifyUser(string $token): void
    {
        $userLogin = $this->db->table('gozzog.user')->select('gozzog.user', ['login'])
            ->where('verification_token', '=', 'verif_token')
            ->bind('verif_token', $token)
            ->fetchAll();

        if ($userLogin) {
            $this->db->update('gozzog.user', ['is_verified' => 1, 'verification_token' => null], ['login' => $userLogin[0]['login']]);
        }
    }

    public function verifierCredentials(string $login, string $password): bool
    {
        $user = $this->db->table('gozzog.user')
            ->where('login', '=', 'login')
            ->bind('login', $login)
            ->fetchAll();
        if (empty($user)) return false;
        return MotDePasse::verifier($password, $user[0]['password']);
    }

    public function editNameUser(string $login, mixed $nom) : bool
    {
        try{
            $this->db->update('gozzog.user', ['nom' => $nom], ['login' => $login]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function editPrenomUser(string $login, mixed $prenom)
    {
        try{
            $this->db->update('gozzog.user', ['prenom' => $prenom], ['login' => $login]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function editMailUser(string $login, mixed $mail)
    {
        try{
            $this->db->update('gozzog.user', ['email' => $mail], ['login' => $login]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


}
