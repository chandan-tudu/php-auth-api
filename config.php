<?php
require_once __DIR__ . '/utils/headers.php';
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__ . '/utils/constants.php';

require_once __DIR__ . '/classes/ErrorHandler.php';
set_error_handler('ErrorHandler::handelError');
set_exception_handler('ErrorHandler::handleException');

require_once __DIR__ . '/classes/Main.php';
