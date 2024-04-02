<?php

namespace App\Service;

use App\Repository\UserRepository;

class UserService extends GeneriqueService implements I_UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
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

}
