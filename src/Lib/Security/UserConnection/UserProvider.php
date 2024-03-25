<?php

namespace App\Lib\Security\UserConnection;

class UserProvider
{
    public function getCurrentUser(): ?string
    {
        return UserHelper::isUserLoggedIn() ? UserHelper::getLoginUtilisateurConnecte() : null;
    }
}
