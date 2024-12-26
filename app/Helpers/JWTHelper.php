<?php

namespace App\Helpers;

use CodeIgniter\HTTP\RequestInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper
{

    /**
     * @param string $email
     * @param string $role
     * @param string $name
     * @return string token encoded with email, role, name
     */
    public static function encodeUser($email, $role, $name)
    {
        $key = getenv('JWT_SECRET');
        $iat = time();
        $exp = $iat + (3600 * 24 );

        $payload = array(
            "iss" => "user_wizardpos",
            "aud" => "user_wizardpos",
            "sub" => "auth",
            "iat" => $iat,
            "exp" => $exp,
            "email" => $email,
            'role' =>  $role,
            'name' => $name,
        );

        $token = JWT::encode($payload, $key, 'HS256');
        return $token;
    }

    public static function decode($jwt)
    {
        try {

            if (empty($jwt)) {
                return  null;
            }
            $key = getenv('JWT_SECRET');
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            return $decoded;
        } catch (\Exception $ex) {
            return null;
        }
    }

    /**
     * @param RequestInterface $request
     * @return mixed decoded token | null 
     */
    public static function removeBearer($header)
    {
        $token = null;
        try {

            if (!(is_null($header) || empty($header))) {
                //remove bearer
                $token = str_replace('Authorization: Bearer ', '', $header);
            }
        } catch (\Exception $ex) {
            $token = null;
        }

        return $token;
    }
}
