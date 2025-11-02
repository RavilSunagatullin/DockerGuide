<?php
$host = getenv('DB_HOST') ?: 'db';
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: 'app_db';
$user = getenv('DB_USER') ?: 'app_user';
$password = getenv('DB_PASSWORD') ?: 'secret';

$mysqli = @new mysqli($host, $user, $password, $dbname, (int) $port);

if ($mysqli->connect_errno) {
    echo 'Не удалось подключиться к MariaDB: ' . $mysqli->connect_error;
    exit;
}

echo 'Успешное подключение к MariaDB!';

$result = $mysqli->query('SELECT NOW() AS current_time');
if ($result) {
    $row = $result->fetch_assoc();
    echo '<br>Текущее время БД: ' . $row['current_time'];
    $result->free();
}

$mysqli->close();
