<?php
require_once __DIR__ . "/config.php";

$hostValue = isset($db_host) ? $db_host : (isset($host) ? $host : null);
$userValue = isset($db_user) ? $db_user : (isset($user) ? $user : null);
$passwordValue = isset($db_password) ? $db_password : (isset($password) ? $password : null);
$dbValue = isset($db_name) ? $db_name : (isset($db) ? $db : null);

if ($hostValue === null || $userValue === null || $passwordValue === null || $dbValue === null) {
    http_response_code(500);
    die("Configuracion de base de datos incompleta");
}

$conn = mysqli_connect($hostValue, $userValue, $passwordValue, $dbValue);

if (!$conn) {
    http_response_code(500);
    die("Error de conexión a base de datos");
}

mysqli_set_charset($conn, "utf8mb4");
?>