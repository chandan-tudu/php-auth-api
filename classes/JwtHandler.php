<?php
require_once __DIR__ . '/Main.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class JwtHandler extends Main
{
    /**
     * @parameter $accessToken = true
     * Means Generate an Access token
     * 
     * @parameter $accessToken = false
     * Means Generate a Refresh token
     */
    public static function encode($payload, bool $accessToken = true): string
    {
        $iss = TOKEN_ISS;
        if ($accessToken) {
            $secret = ACCESS_TOKEN_SECRET;
            $expiry = ACCESS_TOKEN_EXPIRY;
        } else {
            $secret = REF_TOKEN_SECRET;
            $expiry = REF_TOKEN_EXPIRY;
        }
        if (
            ($accessToken &&
                !is_array($payload)) ||
            ($accessToken &&
                (!array_key_exists('user_id', $payload) ||
                    !array_key_exists('token_id', $payload))
            )
        ) {
            static::sendJson(422, 'Payload must be an array with the keys `user_id` and `token_id`');
        }

        $token = array(
            'iss' => $iss,
            'iat' => time(),
            'exp' => time() + $expiry,
            'data' => $payload
        );

        return JWT::encode($token, $secret, 'HS256');
    }

    /**
     * @parameter $accessToken = true
     * Means Decode the Access token
     * 
     * @parameter $accessToken = false
     * Means Decode the Refresh token
     */
    public static function decode(string $token, bool $accessToken = true): mixed
    {
        if ($accessToken) {
            $secret = ACCESS_TOKEN_SECRET;
        } else {
            $secret = REF_TOKEN_SECRET;
        }

        try {
            $decode = JWT::decode($token, new Key($secret, 'HS256'));
            return $decode->data;
        } catch (ExpiredException | SignatureInvalidException $e) {
            static::sendJson(401, $e->getMessage());
        } catch (UnexpectedValueException | Exception $e) {
            static::sendJson(400, $e->getMessage());
        }
    }
}
