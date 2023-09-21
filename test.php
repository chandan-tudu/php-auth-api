<?php
// Ignore this (test.php) file

require_once __DIR__ . "/config.php";

function _testConnection()
{
    require_once __DIR__ . "/classes/Database.php";
    $db = new Database;
    $conn = $db->getConnection();
    if ($conn) echo "Ok";
}

function _testJwtHandler()
{
    require_once __DIR__ . "/classes/JwtHandler.php";

    // $token = JwtHandler::encode($payload);
    // $data = JwtHandler::decode($token);
    // print_r($data);
}
// _testJwtHandler();

function _checkMethod()
{
    require_once __DIR__ . "/classes/Main.php";
    Main::checkMethod("POST");
}
// _checkMethod();


function _token()
{
    require_once __DIR__ . "/classes/Database.php";
    require_once __DIR__ . "/classes/TokenHandler.php";
    $Token = new TokenHandler(new Database);
}
// _token();
