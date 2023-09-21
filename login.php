<?php
$allowMethod = 'POST';
require_once __DIR__ . '/config.php';

use Main as Request;
use Main as Response;

if (Request::checkMethod($allowMethod)) :
    require_once __DIR__ . '/classes/Database.php';
    require_once __DIR__ . '/classes/User.php';

    $Database = new Database();
    $User = new User($Database);
    $user_id = $User->login();

    require_once __DIR__ . '/classes/JwtHandler.php';
    require_once __DIR__ . '/classes/TokenHandler.php';


    $refreshToken = JwtHandler::encode($user_id, false);

    $TokenHandler = new TokenHandler($Database);
    $row = $TokenHandler->whiteList($refreshToken, $user_id);

    $accessToken = JwtHandler::encode([
        'user_id' => $user_id,
        'token_id' => $Database->getConnection()->lastInsertId()
    ]);

    Response::sendJson(200, 'You have successfully logged in.', [
        'access_token' => $accessToken,
        'refresh_token' => $refreshToken
    ]);

endif;
