<?php
require_once __DIR__ . '/JwtHandler.php';
class TokenHandler extends JwtHandler
{
    private $refTokenId;
    function __construct(private Database $DB)
    {
    }

    # Whitelisting Refresh token
    public function whiteList(string $refreshToken, int $user_id)
    {
        $sql = "INSERT INTO `refresh_token`(`token`,`user_id`) VALUES(:token,:user_id)";
        return $this->DB->run_prepare($sql, [
            ':token' => [md5($refreshToken), PDO::PARAM_STR],
            ':user_id' => [$user_id, PDO::PARAM_INT]
        ]);
    }

    # Checking that the refresh token is whitelisted
    public function isWhiteListed($refreshToken): int
    {
        # Converting to md5 format, because refresh tokens are whitelisted in that format
        $refreshToken = md5($refreshToken);

        $sql = "SELECT `id` FROM `refresh_token` WHERE `token`=:token";
        $stmt =  $this->DB->run_prepare($sql, [
            ':token' => [$refreshToken, PDO::PARAM_STR]
        ]);

        if ($stmt->rowCount()) {
            $this->refTokenId = $stmt->fetch(PDO::FETCH_OBJ)->id;
        }
        return $stmt->rowCount();
    }

    # Refresh the access token
    public function refreshToken($refreshToken)
    {
        $user_id = static::decode($refreshToken, false);
        if (!$this->isWhiteListed($refreshToken)) {
            static::sendJson(401, 'Invalid Refresh Token (Refresh token is not whitelisted).');
        }

        $newRefToken = static::encode($user_id, false);
        $sql = "UPDATE `refresh_token` SET `token`=:new_ref_token WHERE `token`=:old_ref_token";

        $this->DB->run_prepare($sql, [
            ':new_ref_token' => [md5($newRefToken), PDO::PARAM_STR],
            ':old_ref_token' => [md5($refreshToken), PDO::PARAM_STR],
        ]);

        $payload = [
            'user_id' => $user_id,
            'token_id' => $this->refTokenId
        ];

        static::sendJson(200, '', [
            'access_token' => static::encode($payload),
            'refresh_token' => $newRefToken
        ]);
    }

    # Removing the Refresh Token from whitelist
    public function destroyRefToken(int $refTokenId)
    {
        $sql = "DELETE FROM `refresh_token` WHERE `id`=:id";

        $this->DB->run_prepare($sql, [
            ':id' => [$refTokenId, PDO::PARAM_INT]
        ]);

        static::sendJson(200, 'You have successfully logged out.');
    }
}
