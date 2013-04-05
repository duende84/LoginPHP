<?php

define('BASE_URL', 'http://localhost/login_example/');

define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
if (BASE_URL == "http://localhost/login_example/") {
    define('DB_USER', 'postgres');
    define('DB_PASS', 'admin');
} 
define('DB_NAME', 'db_login_example');
define('DB_CHAR', 'utf8');
?>