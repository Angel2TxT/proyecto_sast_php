<?php
session_start();
$displayUser = isset($_GET['user']) ? $_GET['user'] : (isset($_SESSION['user']) ? $_SESSION['user'] : 'invitado');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Proyecto SAST</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="page-wrap">
    <nav class="nav-main">
        <div class="nav-links">
            <a class="nav-link" href="index.php">Login</a>
            <a class="nav-link" href="dashboard.php?user=<?php echo urlencode($displayUser); ?>">Dashboard</a>
            <a class="nav-link" href="semgrep_panel.php">Panel Semgrep</a>
            <a class="nav-link" href="INFORME_VULNERABILIDADES.md">Informe</a>
        </div>
        <button id="theme-toggle" class="theme-toggle" type="button">☀️ Modo claro</button>
    </nav>

<div class="panel">
    <div class="card">
        <div class="topbar">
            <div>
                <h1 class="title">Dashboard</h1>
                <p class="subtitle">Zona interna de la aplicación vulnerable.</p>
            </div>
            <div class="topbar-actions">
                <a class="logout-link" href="logout.php">Cerrar sesión</a>
            </div>
        </div>

        <div class="content-box">
            <h2>
            <?php
            // Vulnerable a XSS (intencional para evidencia).
            echo "Bienvenido " . $displayUser;
            ?>
            </h2>
            <p class="subtitle" style="margin-top: 8px; margin-bottom: 0;">
                Esta pantalla mantiene una salida insegura para fines de demostración SAST.
            </p>
            <div style="margin-top: 12px;">
                <a class="btn-link" href="semgrep_panel.php">Abrir panel de resultados Semgrep</a>
            </div>
        </div>
    </div>
</div>
</div>

<script src="theme.js"></script>
</body>
</html>