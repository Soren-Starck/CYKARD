<?php

namespace App\Service;

use App\Repository\I_UserRepository;
use App\Repository\UserRepository;

class UserService extends GeneriqueService implements I_UserService
{
    private I_UserRepository $userRepository;

    public function __construct(I_UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserByLogin(?string $login): array
    {
        return $this->userRepository->getUserByLogin($login);
    }

    public function verifierCredentials(float|bool|int|string|null $loginUtilisateur, float|bool|int|string|null $password): bool
    {
        return $this->userRepository->verifierCredentials($loginUtilisateur, $password);
    }

    public function createUser(\App\Entity\User $user): void
    {
        $this->userRepository->createUser($user);
    }

    public function verifyUser(string $token): void
    {
        $this->userRepository->verifyUser($token);
    }



    public function modifyName(mixed $nom, string $login): array
    {
        if(!$nom) return ['error' => 'Nom is required', 'status' => 400];
        $dbResponse = $this->userRepository->editNameUser($login, $nom);
        if(!$dbResponse) return ['error' => 'Error editing user name', 'status' => 500];
        return $this->getUserByLogin($login);
    }

    public function modifyPrenom(mixed $prenom, string $login): array
    {
        if(!$prenom) return ['error' => 'Prenom is required', 'status' => 400];
        $dbResponse = $this->userRepository->editPrenomUser($login, $prenom);
        if(!$dbResponse) return ['error' => 'Error editing user prenom', 'status' => 500];
        return $this->getUserByLogin($login);
    }
    public function modifyMail(mixed $mail, string $login): array
    {
        if(!$mail) return ['error' => 'Mail is required', 'status' => 400];
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)) return ['error' => 'Invalid mail', 'status' => 400];
        $dbResponse = $this->userRepository->editMailUser($login, $mail);
        if(!$dbResponse) return ['error' => 'Error editing user mail', 'status' => 500];
        return $this->getUserByLogin($login);
    }

    public function modifyPassword(mixed $old_password, mixed $new_password, string $login)
    {
        if(!$old_password || !$new_password) return ['error' => 'Old and new password are required', 'status' => 400];
        if($old_password === $new_password) return ['error' => 'Old and new password are the same', 'status' => 400];
        if(!$this->verifierCredentials($login, $old_password)) return ['error' => 'Invalid old password', 'status' => 400];
        if(strlen($new_password) < 6) return ['error' => 'Password must be at least 6 characters', 'status' => 400];
        $dbResponse = $this->userRepository->editPasswordUser($login, $new_password);
        if(!$dbResponse) return ['error' => 'Error editing user password', 'status' => 500];
        return $this->getUserByLogin($login);
    }
}
