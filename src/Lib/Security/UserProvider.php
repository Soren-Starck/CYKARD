<?php

namespace App\Lib\Security;

class UserProvider
{
    public function getCurrentUser(): ?string
    {
        return UserHelper::isUserLoggedIn() ? UserHelper::getLoginUtilisateurConnecte() : null;
    }
}
