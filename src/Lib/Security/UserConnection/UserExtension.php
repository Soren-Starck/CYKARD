<?php

namespace App\Lib\Security\UserConnection;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_user_logged_in', [UserHelper::class, 'isUserLoggedIn']),
            new TwigFunction('does_user_have_role', [UserHelper::class, 'doesUserHaveRole']),
        ];
    }
}
