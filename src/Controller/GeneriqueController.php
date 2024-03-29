<?php

namespace App\Controller;

use App\Lib\Security\JWT\JsonWebToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class GeneriqueController extends AbstractController
{
    public function getLoginFromJwt(Request $request): string
    {
        $jwt = $request->headers->get('Authorization');
        return JsonWebToken::getLogin($jwt);
    }
}