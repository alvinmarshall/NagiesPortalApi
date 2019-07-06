<?php


namespace App\auth;
define('iat', time());
define('nbf', iat );
define('exp', nbf + 86400); // since 24 hours = 86,400 seconds
use Exception;
use Firebase\JWT\JWT;

class Authentication
{
    public static $error;
    private static $token_data;
    public static $jwt_package = array(
        "key" => "portal_key",
        "alg" => array('HS256'),
        "iss" => "http://infordasgh.com",
        "aud" => "http://infordasgh.com",
        "iat" => iat,
        "nbf" => nbf,
        "exp" => exp
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
                $decode = JWT::decode($token, $key, $alg);
                self::$token_data = self::decodeTokenData($decode);
                return $decode;
            } catch (Exception $e) {
                self::$error = $e->getMessage();
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

        if ($jwt != null && $user_data != null) {
            /** @var string $iss
             * @var string $aud
             * @var int $iat
             * @var int $nbf
             * @var string $key
             */
            extract($jwt);
            /**
             * @var string $id
             * @var string $full_name
             * @var string $username
             * @var string $level
             * @var string $image
             * @var string $exp
             */
            extract($user_data);
            $token = array(
                "iss" => $iss,
                "aud" => $aud,
                "iat" => $iat,
                "nbf" => $nbf,
                "data" => array(
                    "id" => $id,
                    "full_name" => $full_name,
                    "username" => $username,
                    "image" => $image,
                    "level" => $level
                )
            );
            $encode = JWT::encode($token, $key);
            return $encode;
        }
        return $encode;
    }

    public static function isJWTTokenValid($token)
    {
        $decode = null;
        if ($token == null) return false;
        try {
            $decode = self::decodeJWTToken($token, self::$jwt_package['key'], self::$jwt_package['alg']);
            if ($decode) {
                return true;
            }
        } catch (Exception $e) {
            self::$error = $e;
        }
        return false;
    }

    private static function decodeTokenData($decode)
    {
        return $login_user_data = [
            "id" => $decode->data->id,
            "full_name" => $decode->data->full_name,
            "username" => $decode->data->username,
            "image" => $decode->data->image,
            "level" => $decode->data->level
        ];
    }

    public static function getDecodedData(){
        return self::$token_data;
    }

}