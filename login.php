<?php
/**
 * login.php — Página de inicio de sesión
 */
require_once 'auth.php';

// Si ya está logueado, redirigir al dashboard
if (!empty($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error   = '';
$success = '';

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Por favor complete todos los campos.';
    } else {
        $resultado = iniciarSesion($username, $password);
        if ($resultado['ok']) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = $resultado['mensaje'];
        }
    }
}

// Mensajes de error por URL
$mensajesError = [
    'sesion'    => 'Debe iniciar sesión para acceder.',
    'expirada'  => 'Su sesión ha expirado por inactividad. Por favor inicie sesión nuevamente.',
    'logout'    => 'Ha cerrado sesión correctamente.'
];

if (!empty($_GET['error']) && isset($mensajesError[$_GET['error']])) {
    $error = $mensajesError[$_GET['error']];
}
if (!empty($_GET['logout']) && $_GET['logout'] === 'ok') {
    $success = 'Ha cerrado sesión correctamente.';
}

$csrfToken = generarTokenCSRF();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión — Portafolio Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Estilos específicos de login */
        .login-page {
            min-height: 100vh;
            background: #020e25;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #071633;
            border: 1px solid rgba(13, 110, 253, 0.15);
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), 0 0 40px rgba(13, 110, 253, 0.08);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }

        .login-header {
            background: rgba(13, 110, 253, 0.08);
            padding: 2.5rem 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(13, 110, 253, 0.1);
        }

        .login-header .icon-wrapper {
            width: 70px;
            height: 70px;
            background: rgba(13, 110, 253, 0.15);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .login-header .icon-wrapper i {
            font-size: 2rem;
            color: #6ea8fe;
        }

        .login-header h3 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .login-header p {
            color: #9aa7c2;
            font-size: 0.9rem;
            margin: 0;
        }

        .login-body {
            padding: 2rem;
        }

        .form-floating > .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .form-floating > .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            color: #fff;
        }

        .form-floating > label {
            color: #9aa7c2;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #6ea8fe;
        }

        .btn-login {
            background: #0d6efd;
            border: none;
            border-radius: 0.75rem;
            padding: 0.85rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-login:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
        }

        .login-footer {
            text-align: center;
            padding: 1.5rem 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .login-footer a {
            color: #6ea8fe;
            text-decoration: none;
            transition: color 0.2s;
        }

        .login-footer a:hover {
            color: #fff;
        }

        .login-footer p {
            color: #6c757d;
            font-size: 0.85rem;
            margin: 0;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-right: none;
            color: #9aa7c2;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus {
            border-left: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: #0d6efd;
        }

        /* Animación de entrada */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeInUp 0.6s ease;
        }

        /* Toggle password visibility */
        .toggle-password {
            cursor: pointer;
            color: #9aa7c2;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #6ea8fe;
        }
    </style>
</head>
<body class="login-page">

    <div class="login-card">
        <!-- Header -->
        <div class="login-header">
            <div class="icon-wrapper">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h3>Panel Administrativo</h3>
            <p>Inicie sesión para gestionar su portafolio</p>
        </div>

        <!-- Body -->
        <div class="login-body">
            <?php if ($error): ?>
                <?= mostrarAlertaBootstrap('danger', $error) ?>
            <?php endif; ?>
            <?php if ($success): ?>
                <?= mostrarAlertaBootstrap('success', $success) ?>
            <?php endif; ?>

            <form method="POST" action="login.php" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                <!-- Usuario -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Usuario" required autofocus autocomplete="username">
                    <label for="username"><i class="bi bi-person me-1"></i>Usuario</label>
                </div>

                <!-- Contraseña -->
                <div class="form-floating mb-4 position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required autocomplete="current-password">
                    <label for="password"><i class="bi bi-lock me-1"></i>Contraseña</label>
                    <span class="toggle-password position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); z-index: 10;" onclick="togglePassword()">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </span>
                </div>

                <!-- Recordarme -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label text-secondary" for="remember">
                        Recordar mi sesión
                    </label>
                </div>

                <!-- Botón -->
                <button type="submit" class="btn btn-primary btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <a href="index.php"><i class="bi bi-arrow-left me-1"></i>Volver al portafolio</a>
            <p class="mt-3">&copy; <?= date('Y') ?> Sebastián Muñoz</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Auto-dismissing alerts
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    </script>
</body>
</html>
