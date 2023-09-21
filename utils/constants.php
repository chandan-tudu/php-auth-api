<?php
define('DEBUG_MODE', (bool)$_ENV['DEBUG_MODE']);

define('TOKEN_ISS', $_ENV['TOKEN_ISS']);

define('ACCESS_TOKEN_SECRET', $_ENV['ACCESS_TOKEN_SECRET']);
define('ACCESS_TOKEN_EXPIRY', (int)$_ENV['ACCESS_TOKEN_EXPIRY']);

define('REF_TOKEN_SECRET', $_ENV['REF_TOKEN_SECRET']);
define('REF_TOKEN_EXPIRY', (int)$_ENV['REF_TOKEN_EXPIRY']);


define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
