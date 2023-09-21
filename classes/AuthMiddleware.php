<?php
require_once __DIR__ . '/JwtHandler.php';
class AuthMiddleware extends JwtHandler
{
    public static function isAuth(Database $DB, $logout = false)
    {
        # Fetches all HTTP headers from the current request
        $headers = getallheaders();

        # If Authorization token is present in header
        if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {

            # Decode the Token
            $data = static::decode($matches[1]);

            # When isAuth method will be called from logout.php
            if ($logout) {
                static::isRefTokenExist($DB, $data->token_id);
                return $data->token_id;
            }

            return static::fetchUserById($DB, $data->user_id);
        }
        static::sendJson(403, 'Authorization Token is Missing!');
    }

    private static function isRefTokenExist(Database $DB, int $token_id)
    {
        $sql = "SELECT `id` FROM `refresh_token` WHERE `id`=:id";
        $result = $DB->run_prepare($sql, [
            ':id' => [$token_id, PDO::PARAM_INT]
        ]);
        if ($result->rowCount()) return true;
        static::sendJson(401, 'Your token is not valid. Probably you are logged out!');
    }

    private static function fetchUserById(Database $DB, int $id)
    {
        $sql = "SELECT `id`,`name`,`email` FROM `users` WHERE `id`=:id";

        $result = $DB->run_prepare($sql, [
            ':id' => [$id, PDO::PARAM_INT]
        ]);

        if ($result->rowCount()) return $result->fetch(PDO::FETCH_ASSOC);
        static::sendJson(404, 'User Not Found!');
    }
}
