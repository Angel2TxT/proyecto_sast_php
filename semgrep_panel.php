<?php
session_start();
$displayUser = isset($_SESSION['user']) ? $_SESSION['user'] : 'invitado';

$findings = [];
$errorMessage = "";
$ranScan = false;
$lastRunAt = "";
$selectedConfig = "semgrep.yml";
$commandShown = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ranScan = true;
    if (isset($_POST["scan_config"]) && $_POST["scan_config"] === "auto") {
        $selectedConfig = "auto";
    }
    $projectDir = __DIR__;
    $outputPath = $projectDir . DIRECTORY_SEPARATOR . "semgrep-web-report.json";
    $configArg = $selectedConfig === "auto" ? "auto" : "semgrep.yml";
    $command = 'cmd /c "set PYTHONUTF8=1&& set PYTHONIOENCODING=utf-8&& semgrep scan --config=' . $configArg . ' --json --output semgrep-web-report.json ."';
    $commandShown = "semgrep scan --config=" . $configArg . " .";
    $shellOutput = [];
    $exitCode = 0;

    // Ejecuta semgrep en el directorio del proyecto para actualizar el reporte.
    $previousDir = getcwd();
    chdir($projectDir);
    exec($command . " 2>&1", $shellOutput, $exitCode);
    chdir($previousDir);

    if ($exitCode !== 0 || !file_exists($outputPath)) {
        $errorMessage = "No se pudo ejecutar Semgrep. Verifica que semgrep este instalado en el PATH.";
    } else {
        $jsonContent = file_get_contents($outputPath);
        $decoded = json_decode($jsonContent, true);
        if (!is_array($decoded) || !isset($decoded["results"])) {
            $errorMessage = "Semgrep retorno una salida no valida en formato JSON.";
        } else {
            $findings = $decoded["results"];
            $lastRunAt = date("Y-m-d H:i:s");
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Semgrep | Proyecto SAST</title>
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
                <h1 class="title">Panel Semgrep</h1>
                <p class="subtitle">Resultados de Semgrep para tus pruebas SAST.</p>
            </div>
            <div class="topbar-actions">
                <a class="logout-link" href="dashboard.php?user=<?php echo urlencode($displayUser); ?>">Volver</a>
            </div>
        </div>

        <form method="POST" class="scan-form">
            <div class="field" style="max-width: 320px;">
                <label for="scan_config">Configuracion de escaneo</label>
                <select id="scan_config" name="scan_config" class="select-control">
                    <option value="semgrep.yml" <?php echo $selectedConfig === "semgrep.yml" ? "selected" : ""; ?>>Reglas locales (semgrep.yml)</option>
                    <option value="auto" <?php echo $selectedConfig === "auto" ? "selected" : ""; ?>>Auto (registry)</option>
                </select>
            </div>
            <button class="btn" type="submit">Ejecutar escaneo Semgrep</button>
        </form>

        <?php if ($errorMessage !== "") { ?>
            <div class="alert alert-danger" style="margin-top: 12px;"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, "UTF-8"); ?></div>
        <?php } ?>

        <?php if ($ranScan && $errorMessage === "") { ?>
            <div class="content-box" style="margin-top: 14px;">
                <p><strong>Total de hallazgos:</strong> <?php echo count($findings); ?></p>
                <p class="finding-meta" style="margin-top: 8px;">Comando: <code><?php echo htmlspecialchars($commandShown, ENT_QUOTES, "UTF-8"); ?></code></p>
                <p class="finding-meta">Ultima ejecucion: <code><?php echo htmlspecialchars($lastRunAt, ENT_QUOTES, "UTF-8"); ?></code></p>
            </div>

            <div class="findings-list">
                <?php if (count($findings) === 0) { ?>
                    <div class="content-box" style="margin-top: 12px;">
                        <p>No se detectaron hallazgos con las reglas actuales.</p>
                    </div>
                <?php } ?>

                <?php foreach ($findings as $finding) { ?>
                    <?php
                    $checkId = isset($finding["check_id"]) ? $finding["check_id"] : "sin-id";
                    $message = isset($finding["extra"]["message"]) ? $finding["extra"]["message"] : "Sin descripcion";
                    $severity = isset($finding["extra"]["severity"]) ? strtoupper($finding["extra"]["severity"]) : "INFO";
                    $path = isset($finding["path"]) ? $finding["path"] : "desconocido";
                    $line = isset($finding["start"]["line"]) ? $finding["start"]["line"] : "?";
                    ?>
                    <div class="finding-card">
                        <div class="finding-head">
                            <span class="badge"><?php echo htmlspecialchars($severity, ENT_QUOTES, "UTF-8"); ?></span>
                            <code><?php echo htmlspecialchars($checkId, ENT_QUOTES, "UTF-8"); ?></code>
                        </div>
                        <p class="finding-message"><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
                        <p class="finding-meta">
                            Archivo: <code><?php echo htmlspecialchars($path, ENT_QUOTES, "UTF-8"); ?></code>
                            | Linea: <code><?php echo htmlspecialchars((string)$line, ENT_QUOTES, "UTF-8"); ?></code>
                        </p>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>
</div>

<script src="theme.js"></script>
</body>
</html>
