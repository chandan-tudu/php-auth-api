<?php
$allowMethod = 'GET';
require_once __DIR__ . '/config.php';

use Main as Request;

Request::checkMethod($allowMethod);

require_once __DIR__ . '/classes/Database.php';
$Database = new Database;
require_once __DIR__ . '/classes/AuthMiddleware.php';

$i_want_to_logout = true;
$refTokenId = AuthMiddleware::isAuth($Database, $i_want_to_logout);

require_once __DIR__ . '/classes/TokenHandler.php';
$TokenHandler = new TokenHandler($Database);
$TokenHandler->destroyRefToken($refTokenId);
