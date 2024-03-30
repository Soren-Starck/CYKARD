<?php

namespace App\Service;

use App\Repository\UserRepository;

class UserService extends GeneriqueService
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
}