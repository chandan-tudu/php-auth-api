<?php
$allowMethod = 'POST';
require_once __DIR__ . '/config.php';

use Main as Request;

Request::checkMethod($allowMethod);

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/User.php';
$Database = new Database();
$User = new User($Database);
$User->register();
