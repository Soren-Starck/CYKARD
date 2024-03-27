<?php

namespace App\Lib\Security\JWT;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JsonWebToken
{
    private static string $jsonSecret = 'f2689ead7c69ced12a0c9c7a0bf5a2483d1dbbef3e6b88cbc5354096c99d529a';

//    public function __construct(string $jsonSecret)
//    {
//        self::$jsonSecret = $jsonSecret;
//    }

    public static function encoder(array $contenu): string
    {
        return JWT::encode($contenu, self::$jsonSecret, 'HS256');
    }

    public static function decoder(string $jwt): array
    {
        try {
            $decoded = JWT::decode($jwt, new Key(self::$jsonSecret, 'HS256'));
            return (array)$decoded;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public static function getLogin(string $jwt): string
    {
        $decoded = self::decoder(substr($jwt, 7));
        return $decoded['login'];
    }
}