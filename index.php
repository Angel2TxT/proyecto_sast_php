<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Proyecto SAST</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="page-wrap">
    <nav class="nav-main">
        <div class="nav-links">
            <a class="nav-link" href="index.php">Login</a>
            <a class="nav-link" href="dashboard.php?user=<?php echo urlencode(isset($_SESSION['user']) ? $_SESSION['user'] : 'invitado'); ?>">Dashboard</a>
            <a class="nav-link" href="semgrep_panel.php">Panel Semgrep</a>
            <a class="nav-link" href="INFORME_VULNERABILIDADES.md">Informe</a>
        </div>
        <button id="theme-toggle" class="theme-toggle" type="button">☀️ Modo claro</button>
    </nav>

    <div class="container">
        <div class="card">
        <h1 class="title">Iniciar sesión</h1>
        <p class="subtitle">Entorno de práctica para análisis SAST en PHP.</p>

        <?php if (isset($_GET['error']) && $_GET['error'] === '1') { ?>
            <div class="alert alert-danger">Usuario o contraseña incorrectos.</div>
        <?php } ?>

        <form action="login.php" method="POST">
            <div class="field">
                <label for="user">Usuario</label>
                <input id="user" type="text" name="user" placeholder="Ingresa tu usuario" required>
            </div>
            <div class="field">
                <label for="pass">Contraseña</label>
                <input id="pass" type="password" name="pass" placeholder="Ingresa tu contraseña" required>
            </div>
            <button class="btn" type="submit">Ingresar</button>
        </form>
        </div>
    </div>
</div>
<script src="theme.js"></script>
</body>
</html>