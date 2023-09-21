<?php
$allowMethod = 'GET';
require_once __DIR__ . '/config.php';

use Main as Request;
use Main as Response;

Request::checkMethod($allowMethod);

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/AuthMiddleware.php';

$user = AuthMiddleware::isAuth(new Database);
Response::sendJson(200, '', $user);
