# Informe SAST: Vulnerabilidades y correcciones

## Alcance

Analisis de aplicacion PHP de login (`index.php`, `login.php`, `dashboard.php`, `conexion.php`, `logout.php`) usando enfoque SAST con Semgrep.

---

## 1) SQL Injection (autenticacion)

- **Archivo afectado:** `login.php`
- **Severidad estimada:** Alta

### Vulnerabilidad detectada

Se construia la consulta SQL concatenando directamente datos de entrada del usuario (`user`, `pass`), permitiendo inyeccion SQL.

Ejemplo de patron vulnerable (estado previo):

```php
$query = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
```

### Correccion aplicada

Se reemplazo por sentencia preparada con `mysqli_prepare` y `mysqli_stmt_bind_param`, evitando que la entrada sea interpretada como SQL.

Estado corregido (actual):

```php
$stmt = mysqli_prepare($conn, "SELECT username FROM users WHERE username = ? AND password = ?");
mysqli_stmt_bind_param($stmt, "ss", $user, $pass);
```

### Resultado

Se mitiga el riesgo de bypass de autenticacion y manipulacion de consultas.

---

## 2) XSS reflejado/persistente en bienvenida

- **Archivo afectado:** `dashboard.php`
- **Severidad estimada:** Alta

### Vulnerabilidad detectada

Se mostraba directamente una entrada controlada por usuario sin escape HTML.

Patron vulnerable (estado previo):

```php
echo "Bienvenido " . $_GET['user'];
```

### Correccion aplicada

Se elimino el uso de `$_GET` para el nombre mostrado y se toma desde sesion. Ademas, se aplica escape de salida con `htmlspecialchars`.

Estado corregido (actual):

```php
$safeUser = htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8');
echo "Bienvenido " . $safeUser;
```

### Resultado

Se previene la ejecucion de scripts inyectados en el navegador del usuario.

---

## 3) Session Fixation (manejo de sesion)

- **Archivo afectado:** `login.php`
- **Severidad estimada:** Media

### Vulnerabilidad detectada

No se regeneraba el identificador de sesion tras autenticar, lo que podia facilitar fijacion de sesion.

### Correccion aplicada

Se agrego regeneracion del ID de sesion inmediatamente despues de login exitoso.

Estado corregido (actual):

```php
session_regenerate_id(true);
$_SESSION['user'] = $user;
```

### Resultado

Se reduce el riesgo de secuestro de sesion tras autenticacion.

---

## 4) Divulgacion de informacion sensible (errores de BD)

- **Archivo afectado:** `conexion.php`
- **Severidad estimada:** Media

### Vulnerabilidad detectada

El mensaje de error devolvia detalle interno de conexion (`mysqli_connect_error()`), exponiendo informacion sensible.

Patron vulnerable (estado previo):

```php
die("Error de conexión: " . mysqli_connect_error());
```

### Correccion aplicada

Se reemplazo por mensaje generico para usuario y codigo HTTP 500.

Estado corregido (actual):

```php
http_response_code(500);
die("Error de conexión a base de datos");
```

### Resultado

Se evita filtrar detalles internos utiles para un atacante.

---

## Evidencia de ejecucion SAST (Semgrep)

Comandos recomendados para documentar evidencia:

```powershell
semgrep --config p/php --config p/security-audit .
semgrep --config semgrep.yml .
semgrep --config p/php --config p/security-audit --json --output semgrep-report.json .
```

---

## Conclusiones

- Se corrigieron vulnerabilidades criticas de inyeccion SQL y XSS.
- Se fortalecio el manejo de sesion y de errores.
- El proyecto queda mejor preparado para evaluaciones SAST y para presentacion academica.
