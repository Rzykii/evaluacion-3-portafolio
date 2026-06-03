<?php
/**
 * admin/mensajes.php — Gestión de mensajes de contacto
 */
require_once '../auth.php';
verificarSesion();
require_once '../db.php';

$usuario = obtenerUsuarioActual();
$csrfToken = generarTokenCSRF();

$mensaje = '';
$tipoMensaje = '';

// Marcar como leído
if (isset($_GET['accion']) && $_GET['accion'] === 'leer' && !empty($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("UPDATE contacto SET leido = 1 WHERE id = ?");
        $stmt->execute([(int)$_GET['id']]);
        header('Location: mensajes.php');
        exit;
    } catch (PDOException $e) {
        $mensaje = 'Error al marcar como leído.';
        $tipoMensaje = 'danger';
    }
}

// Eliminar mensaje
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && !empty($_GET['id'])) {
    if (!validarTokenCSRF($_GET['csrf_token'] ?? '')) {
        $mensaje = 'Token de seguridad inválido.';
        $tipoMensaje = 'danger';
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM contacto WHERE id = ?");
            $stmt->execute([(int)$_GET['id']]);
            $mensaje = 'Mensaje eliminado correctamente.';
            $tipoMensaje = 'success';
        } catch (PDOException $e) {
            $mensaje = 'Error al eliminar el mensaje.';
            $tipoMensaje = 'danger';
        }
    }
}

// Obtener mensajes
try {
    $mensajes = $pdo->query("SELECT * FROM contacto ORDER BY fecha DESC")->fetchAll();
} catch (PDOException $e) {
    $mensajes = [];
}

// Estadísticas
$totalMensajes = count($mensajes);
$noLeidos = count(array_filter($mensajes, fn($m) => !$m['leido']));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes — Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="../dashboard.php" class="sidebar-brand">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>Admin Panel</span>
                </a>
            </div>

            <div class="sidebar-user">
                <div class="avatar"><i class="bi bi-person"></i></div>
                <div class="info">
                    <div class="name"><?= htmlspecialchars($usuario['nombre']) ?></div>
                    <div class="role">Administrador</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">Principal</div>
                <a href="../dashboard.php" class="nav-link">
                    <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                </a>

                <div class="nav-section mt-3">Gestión de Contenido</div>
                <a href="biografia.php" class="nav-link">
                    <i class="bi bi-person-vcard"></i><span>Biografía</span>
                </a>
                <a href="habilidades.php" class="nav-link">
                    <i class="bi bi-stars"></i><span>Habilidades</span>
                </a>
                <a href="tecnologias.php" class="nav-link">
                    <i class="bi bi-cpu"></i><span>Tecnologías</span>
                </a>
                <a href="proyectos.php" class="nav-link">
                    <i class="bi bi-folder"></i><span>Proyectos</span>
                </a>

                <div class="nav-section mt-3">Contacto</div>
                <a href="mensajes.php" class="nav-link active">
                    <i class="bi bi-envelope"></i><span>Mensajes</span>
                    <?php if ($noLeidos > 0): ?>
                        <span class="badge bg-danger ms-auto"><?= $noLeidos ?></span>
                    <?php endif; ?>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="../logout.php" class="btn-logout" onclick="return confirm('¿Está seguro de cerrar sesión?')">
                    <i class="bi bi-box-arrow-left"></i><span>Cerrar Sesión</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <div>
                    <h1><i class="bi bi-envelope me-2"></i>Mensajes de Contacto</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Mensajes</li>
                        </ol>
                    </nav>
                </div>
                <button class="btn btn-outline-light d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
            </div>

            <?php if ($mensaje): ?>
                <?= mostrarAlertaBootstrap($tipoMensaje, $mensaje) ?>
            <?php endif; ?>

            <!-- Estadísticas rápidas -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon blue"><i class="bi bi-envelope"></i></div>
                        <div class="stat-info">
                            <div class="stat-value"><?= $totalMensajes ?></div>
                            <div class="stat-label">Total mensajes</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon orange"><i class="bi bi-envelope-exclamation"></i></div>
                        <div class="stat-info">
                            <div class="stat-value"><?= $noLeidos ?></div>
                            <div class="stat-label">Sin leer</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon green"><i class="bi bi-envelope-open"></i></div>
                        <div class="stat-info">
                            <div class="stat-value"><?= $totalMensajes - $noLeidos ?></div>
                            <div class="stat-label">Leídos</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de mensajes -->
            <div class="admin-card">
                <div class="card-header">
                    <h5>Todos los mensajes</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($mensajes)): ?>
                        <div class="text-center py-5 text-secondary">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                            No hay mensajes aún.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Estado</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Asunto</th>
                                        <th>Mensaje</th>
                                        <th>Fecha</th>
                                        <th style="width: 100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mensajes as $msg): ?>
                                        <tr style="<?= !$msg['leido'] ? 'background: rgba(13,110,253,0.04);' : '' ?>">
                                            <td>
                                                <?php if (!$msg['leido']): ?>
                                                    <span class="badge bg-danger" style="font-size: 0.65rem;">Nuevo</span>
                                                <?php else: ?>
                                                    <span class="badge-estado activo" style="font-size: 0.65rem;">Leído</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="fw-medium text-white"><?= htmlspecialchars($msg['nombre']) ?></td>
                                            <td>
                                                <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" style="color: var(--primary-light); text-decoration: none; font-size: 0.85rem;">
                                                    <?= htmlspecialchars($msg['email']) ?>
                                                </a>
                                            </td>
                                            <td style="font-size: 0.85rem;"><?= htmlspecialchars($msg['asunto']) ?></td>
                                            <td style="font-size: 0.85rem; max-width: 300px;" class="text-secondary">
                                                <?= htmlspecialchars(substr($msg['mensaje'], 0, 80)) ?><?= strlen($msg['mensaje']) > 80 ? '...' : '' ?>
                                            </td>
                                            <td style="font-size: 0.8rem; white-space: nowrap;" class="text-secondary">
                                                <?= date('d/m/Y H:i', strtotime($msg['fecha'])) ?>
                                            </td>
                                            <td>
                                                <?php if (!$msg['leido']): ?>
                                                    <a href="mensajes.php?accion=leer&id=<?= $msg['id'] ?>"
                                                        class="btn-action view" title="Marcar como leído">
                                                        <i class="bi bi-envelope-open"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="mensajes.php?accion=eliminar&id=<?= $msg['id'] ?>&csrf_token=<?= urlencode($csrfToken) ?>"
                                                    class="btn-action delete" title="Eliminar"
                                                    onclick="return confirm('¿Eliminar este mensaje?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
    </script>
</body>
</html>
