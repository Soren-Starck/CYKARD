<?php

namespace App\Lib\Security\JWT;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JsonWebToken
{

    public static function encoder(array $contenu): string
    {
        $jsonSecret = $_ENV['JWT_SECRET_KEY'];
        return JWT::encode($contenu, $jsonSecret, 'HS256');
    }

    public static function decoder(string $jwt): array
    {
        try {
            $decoded = JWT::decode($jwt, new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
            return (array)$decoded;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public static function getLogin(string|null $jwt): string
    {
        if (is_null($jwt)) return "";
        $jwt = str_replace('"', '', $jwt);
        $decoded = self::decoder($jwt);
        return $decoded['login'];
    }
}