<?php


namespace App\auth;

use Exception;
use Firebase\JWT\JWT;

class Authentication
{
    public static $role;
    public static $error;
    public static $jwt_package = array(
        "key" => "portal_key",
        "alg" => array('HS256'),
        "iss" => "http://infordasgh.com",
        "aud" => "http://infordasgh.com",
        "iat" => 1356999524,
        "nbf" => 1357000000
    );


    /**decrypt web token to access user data
     * @param $token
     * @param $key
     * @param $alg
     * @return object|null
     */
    private static function decodeJWTToken($token, $key, $alg)
    {
        $decode = null;
        if ($token) {
            try {
                $decode = JWT::decode($token, $key,$alg);
                self::$role = $decode->data->role;
                return $decode;
            } catch (Exception $e) {
                self::$error = $e->getMessage();
                echo self::$error;
                return null;
            }
        }
        return $decode;
    }

    /**encrypt user data with jwt as web token
     * @param $jwt
     * @param $user_data
     * @return string
     */
    public static function encodeJWTToken($jwt, $user_data)
    {
        $encode = null;

        if ($jwt!=null && $user_data != null) {
            /** @var string $iss
             * @var string $aud
             * @var int $iat
             * @var int $nbf
             * @var string $key
             */
            extract($jwt);
            /**
             * @var string $id
             * @var string $firstname
             * @var string $lastname
             * @var string $email
             * @var string $role
             */
            extract($user_data);
            $token = array(
                "iss" => $iss,
                "aud" => $aud,
                "iat" => $iat,
                "nbf" => $nbf,
                "data" => array(
                    "id" => $id,
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "email" => $email,
                    "role" => $role
                )
            );
            $encode = JWT::encode($token, $key);
            return $encode;
        }
        return $encode;
    }

    public static function isJWTTokenValid($token){
        $decode = null;
        if($token == null) return false;
        try {
            $decode = self::decodeJWTToken($token,self::$jwt_package['key'],self::$jwt_package['alg']);
            if ($decode){
                return true;
            }
        } catch (Exception $e) {
            self::$error = $e;
        }
        return false;
    }

}