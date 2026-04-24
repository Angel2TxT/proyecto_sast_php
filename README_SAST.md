# SAST con Semgrep (PHP)

## 1) Instalar Semgrep

```powershell
python -m pip install --upgrade pip
python -m pip install semgrep
```

## 2) Ejecutar analisis

### Reglas sugeridas de Semgrep (recomendado)

```powershell
semgrep --config p/php --config p/security-audit .
```

### Reglas locales del proyecto

```powershell
semgrep --config semgrep.yml .
```

## 3) Generar reporte JSON para evidencia

```powershell
semgrep --config p/php --config p/security-audit --json --output semgrep-report.json .
```

## 4) Hallazgos corregidos en este proyecto

- SQL Injection en `login.php` (ahora usa sentencias preparadas).
- XSS reflejado en `dashboard.php` (ahora usa `htmlspecialchars` y sesion).
- Fijacion de sesion en login (ahora usa `session_regenerate_id(true)`).
- Fuga de errores internos en DB (`conexion.php` ya no imprime `mysqli_connect_error()`).
