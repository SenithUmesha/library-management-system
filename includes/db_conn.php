<?php

$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$db = getenv('DB_DATABASE') ?: 'library_db';
$port = (int) (getenv('DB_PORT') ?: 3306);

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    throw new RuntimeException('Unable to connect to the library database.');
}

mysqli_set_charset($conn, 'utf8mb4');
