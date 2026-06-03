<?php
/**
 * auth.php — Sistema de autenticación y utilidades compartidas
 *
 * Incluir al inicio de cada página que requiera protección:
 *   require_once 'auth.php';
 *   verificarSesion();
 */

// ============================================================
// 1. CONFIGURACIÓN DE SESIÓN
// ============================================================

// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Zona horaria
date_default_timezone_set('America/Santiago');

// ============================================================
// 2. CONSTANTES
// ============================================================

define('SESION_DURACION_MINUTOS', 60);        // Sesión expira tras 60 min de inactividad
define('MAX_INTENTOS_LOGIN', 5);               // Intentos fallidos antes de bloqueo
define('TIEMPO_BLOQUEO_MINUTOS', 15);          // Minutos de bloqueo tras exceder intentos

// ============================================================
// 3. FUNCIÓN: verificarSesion()
//    Llámala al inicio de cada página protegida
// ============================================================

function verificarSesion(): void
{
    // ¿Hay sesión activa?
    if (empty($_SESSION['usuario_id']) || empty($_SESSION['usuario_username'])) {
        header('Location: ../login.php?error=sesion');
        exit;
    }

    // ¿Expiró por inactividad?
    if (!empty($_SESSION['ultimo_acceso'])) {
        $inactivo = time() - $_SESSION['ultimo_acceso'];
        $limite   = SESION_DURACION_MINUTOS * 60;

        if ($inactivo > $limite) {
            cerrarSesion();
            header('Location: ../login.php?error=expirada');
            exit;
        }
    }

    // Actualizar timestamp de último acceso
    $_SESSION['ultimo_acceso'] = time();
}

// ============================================================
// 4. FUNCIÓN: verificarSesionPublica()
//    Para el index.php - solo verifica si hay sesión para mostrar el botón admin
// ============================================================

function verificarSesionPublica(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['usuario_id']) || empty($_SESSION['usuario_username'])) {
        return false;
    }

    // Verificar expiración
    if (!empty($_SESSION['ultimo_acceso'])) {
        $inactivo = time() - $_SESSION['ultimo_acceso'];
        $limite   = SESION_DURACION_MINUTOS * 60;

        if ($inactivo > $limite) {
            cerrarSesion();
            return false;
        }
    }

    $_SESSION['ultimo_acceso'] = time();
    return true;
}

// ============================================================
// 5. FUNCIÓN: iniciarSesion($username, $password)
//    Retorna array ['ok'=>bool, 'mensaje'=>string]
// ============================================================

function iniciarSesion(string $username, string $password): array
{
    require_once 'db.php';

    // Verificar bloqueo por intentos fallidos
    $bloqueoKey = 'bloqueo_' . md5($username);
    if (!empty($_SESSION[$bloqueoKey])) {
        $tiempoRestante = $_SESSION[$bloqueoKey] - time();
        if ($tiempoRestante > 0) {
            $minutos = ceil($tiempoRestante / 60);
            return [
                'ok'      => false,
                'mensaje' => "Cuenta bloqueada. Intente nuevamente en {$minutos} minuto(s)."
            ];
        } else {
            unset($_SESSION[$bloqueoKey]);
            unset($_SESSION['intentos_' . md5($username)]);
        }
    }

    // Buscar usuario en la BD
    try {
        $stmt = $pdo->prepare("SELECT id, username, password, nombre, email, activo FROM usuarios WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            registrarIntentoFallido($username);
            return ['ok' => false, 'mensaje' => 'Usuario o contraseña incorrectos.'];
        }

        if (!$usuario['activo']) {
            return ['ok' => false, 'mensaje' => 'Cuenta desactivada. Contacte al administrador.'];
        }

        // Verificar contraseña
        if (!password_verify($password, $usuario['password'])) {
            registrarIntentoFallido($username);
            return ['ok' => false, 'mensaje' => 'Usuario o contraseña incorrectos.'];
        }

        // Éxito: crear sesión
        $_SESSION['usuario_id']       = $usuario['id'];
        $_SESSION['usuario_username'] = $usuario['username'];
        $_SESSION['usuario_nombre']   = $usuario['nombre'];
        $_SESSION['usuario_email']    = $usuario['email'];
        $_SESSION['ultimo_acceso']    = time();
        $_SESSION['login_time']       = date('Y-m-d H:i:s');

        // Limpiar intentos fallidos
        unset($_SESSION['intentos_' . md5($username)]);
        unset($_SESSION[$bloqueoKey]);

        // Actualizar último acceso (opcional: podríamos agregar campo last_login)
        return ['ok' => true, 'mensaje' => 'Bienvenido, ' . $usuario['nombre']];

    } catch (PDOException $e) {
        error_log('Error login: ' . $e->getMessage());
        return ['ok' => false, 'mensaje' => 'Error del sistema. Intente más tarde.'];
    }
}

// ============================================================
// 6. FUNCIÓN: registrarIntentoFallido($username)
// ============================================================

function registrarIntentoFallido(string $username): void
{
    $intentosKey = 'intentos_' . md5($username);
    $bloqueoKey  = 'bloqueo_'  . md5($username);

    if (empty($_SESSION[$intentosKey])) {
        $_SESSION[$intentosKey] = 1;
    } else {
        $_SESSION[$intentosKey]++;
    }

    if ($_SESSION[$intentosKey] >= MAX_INTENTOS_LOGIN) {
        $_SESSION[$bloqueoKey] = time() + (TIEMPO_BLOQUEO_MINUTOS * 60);
    }
}

// ============================================================
// 7. FUNCIÓN: cerrarSesion()
// ============================================================

function cerrarSesion(): void
{
    // Limpiar todas las variables de sesión
    $_SESSION = [];

    // Destruir cookie de sesión si existe
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'secure'   => false,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
    }

    // Destruir sesión
    session_destroy();
}

// ============================================================
// 8. FUNCIÓN: obtenerUsuarioActual()
// ============================================================

function obtenerUsuarioActual(): ?array
{
    if (empty($_SESSION['usuario_id'])) {
        return null;
    }

    return [
        'id'       => $_SESSION['usuario_id'],
        'username' => $_SESSION['usuario_username'],
        'nombre'   => $_SESSION['usuario_nombre'],
        'email'    => $_SESSION['usuario_email']
    ];
}

// ============================================================
// 9. FUNCIÓN: generarTokenCSRF()
// ============================================================

function generarTokenCSRF(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// ============================================================
// 10. FUNCIÓN: validarTokenCSRF($token)
// ============================================================

function validarTokenCSRF(?string $token): bool
{
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// ============================================================
// 11. FUNCIÓN: mostrarAlertaBootstrap($tipo, $mensaje)
// ============================================================

function mostrarAlertaBootstrap(string $tipo, string $mensaje): string
{
    $iconos = [
        'success' => 'bi-check-circle-fill',
        'danger'  => 'bi-exclamation-triangle-fill',
        'warning' => 'bi-exclamation-triangle-fill',
        'info'    => 'bi-info-circle-fill'
    ];
    $icono = $iconos[$tipo] ?? 'bi-info-circle-fill';

    return "
        <div class='alert alert-{$tipo} alert-dismissible fade show d-flex align-items-center' role='alert'>
            <i class='bi {$icono} me-2'></i>
            <div>{$mensaje}</div>
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
        </div>
    ";
}

// ============================================================
// 12. FUNCIÓN: redirigirSiNoAutenticado()
//    Versión corta para páginas en carpeta raíz
// ============================================================

function redirigirSiNoAutenticado(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit;
    }

    // Verificar expiración
    if (!empty($_SESSION['ultimo_acceso'])) {
        $inactivo = time() - $_SESSION['ultimo_acceso'];
        $limite   = SESION_DURACION_MINUTOS * 60;
        if ($inactivo > $limite) {
            cerrarSesion();
            header('Location: login.php?error=expirada');
            exit;
        }
    }

    $_SESSION['ultimo_acceso'] = time();
}
