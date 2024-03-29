<?php

namespace App\Service;

use App\Lib\Security\JWT\JsonWebToken;
use Symfony\Component\HttpFoundation\Request;

abstract class GeneriqueService
{

    protected function getLoginFromJwt(Request $request): string
    {
        $jwt = $request->headers->get('Authorization');
        return JsonWebToken::getLogin($jwt);
    }

}