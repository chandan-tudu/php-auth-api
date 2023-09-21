<?php
$allowMethod = 'POST';
require_once __DIR__ . '/config.php';

use Main as Request;
use Main as Response;

Request::checkMethod($allowMethod);

require_once __DIR__ . '/classes/Database.php';
$Database = new Database;

$data = json_decode(file_get_contents('php://input'));
if (!isset($data->refresh_token) || !is_string($data->refresh_token)) {
    Response::sendJson(422, 'Please Provide Refresh Token as a String.', ['field' => 'refresh_token']);
}

require_once __DIR__ . '/classes/TokenHandler.php';
$TokenHandler = new TokenHandler($Database);
$TokenHandler->refreshToken($data->refresh_token);
