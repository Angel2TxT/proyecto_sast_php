<?php
session_start();
include("conexion.php");

// Entrada sin validar (intencional para laboratorio SAST).
$user = $_POST['user'];
$pass = $_POST['pass'];

// Vulnerable a SQL Injection (intencional para evidencia).
$query = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['user'] = $user;
    header("Location: dashboard.php?user=$user");
    exit();
} else {
    header("Location: index.php?error=1");
    exit();
}
?>