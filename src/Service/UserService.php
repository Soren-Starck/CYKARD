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
}