<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/configJWT.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    public static function generate(array $payload, int $ttl = 3600): string
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + $ttl;
        return JWT::encode($payload, JWT_SECRET, 'HS256');
    }
    
    public static function verify(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }
}
