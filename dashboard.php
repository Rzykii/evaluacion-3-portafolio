<?php
/**
 * dashboard.php — Panel administrativo principal
 */
require_once 'auth.php';
verificarSesion();
require_once 'db.php';

$usuario = obtenerUsuarioActual();

// Contadores para estadísticas
try {
    $totalHabilidades = $pdo->query("SELECT COUNT(*) FROM habilidades WHERE activo = 1")->fetchColumn();
    $totalTecnologias = $pdo->query("SELECT COUNT(*) FROM tecnologias WHERE activo = 1")->fetchColumn();
    $totalProyectos   = $pdo->query("SELECT COUNT(*) FROM proyectos WHERE activo = 1")->fetchColumn();
    $totalMensajes    = $pdo->query("SELECT COUNT(*) FROM contacto WHERE leido = 0")->fetchColumn();
} catch (PDOException $e) {
    $totalHabilidades = $totalTecnologias = $totalProyectos = $totalMensajes = 0;
}

// Mensajes recientes
try {
    $mensajesRecientes = $pdo->query(
        "SELECT * FROM contacto ORDER BY fecha DESC LIMIT 5"
    )->fetchAll();
} catch (PDOException $e) {
    $mensajesRecientes = [];
}

// Datos de bienvenida según hora
$hora = (int) date('G');
if ($hora >= 5 && $hora < 12) {
    $saludo = 'Buenos días';
} elseif ($hora >= 12 && $hora < 19) {
    $saludo = 'Buenas tardes';
} else {
    $saludo = 'Buenas noches';
}

$csrfToken = generarTokenCSRF();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Portafolio Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar Overlay (mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="dashboard.php" class="sidebar-brand">
                    <i class="bi bi-grid-3x3-gap"></i>
                    <span>Admin Panel</span>
                </a>
            </div>

            <div class="sidebar-user">
                <div class="avatar">
                    <i class="bi bi-person"></i>
                </div>
                <div class="info">
                    <div class="name"><?= htmlspecialchars($usuario['nombre']) ?></div>
                    <div class="role">Administrador</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">Principal</div>
                <a href="dashboard.php" class="nav-link active">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <div class="nav-section mt-3">Gestión de Contenido</div>
                <a href="admin/biografia.php" class="nav-link">
                    <i class="bi bi-person-vcard"></i>
                    <span>Biografía</span>
                </a>
                <a href="admin/habilidades.php" class="nav-link">
                    <i class="bi bi-stars"></i>
                    <span>Habilidades</span>
                </a>
                <a href="admin/tecnologias.php" class="nav-link">
                    <i class="bi bi-cpu"></i>
                    <span>Tecnologías</span>
                </a>
                <a href="admin/proyectos.php" class="nav-link">
                    <i class="bi bi-folder"></i>
                    <span>Proyectos</span>
                </a>

                <div class="nav-section mt-3">Contacto</div>
                <a href="admin/mensajes.php" class="nav-link">
                    <i class="bi bi-envelope"></i>
                    <span>Mensajes</span>
                    <?php if ($totalMensajes > 0): ?>
                        <span class="badge bg-danger ms-auto"><?= $totalMensajes ?></span>
                    <?php endif; ?>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="logout.php" class="btn-logout" onclick="return confirm('¿Está seguro de cerrar sesión?')">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="content-header">
                <div>
                    <h1><?= $saludo ?>, <?= htmlspecialchars(explode(' ', $usuario['nombre'])[0]) ?> 👋</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </nav>
                </div>
                <button class="btn btn-outline-light d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
            </div>

            <!-- Mensajes flash -->
            <?php if (!empty($_SESSION['flash_success'])): ?>
                <?= mostrarAlertaBootstrap('success', $_SESSION['flash_success']) ?>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>
            <?php if (!empty($_SESSION['flash_error'])): ?>
                <?= mostrarAlertaBootstrap('danger', $_SESSION['flash_error']) ?>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <!-- Estadísticas -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="bi bi-stars"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value"><?= $totalHabilidades ?></div>
                            <div class="stat-label">Habilidades</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value"><?= $totalTecnologias ?></div>
                            <div class="stat-label">Tecnologías</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="bi bi-folder"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value"><?= $totalProyectos ?></div>
                            <div class="stat-label">Proyectos</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value"><?= $totalMensajes ?></div>
                            <div class="stat-label">Mensajes nuevos</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Accesos rápidos -->
                <div class="col-lg-8">
                    <div class="admin-card mb-4">
                        <div class="card-header">
                            <h5><i class="bi bi-lightning me-2"></i>Accesos Rápidos</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <a href="admin/biografia.php" class="text-decoration-none">
                                        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: rgba(13,110,253,0.08); border: 1px solid var(--dark-border);">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-person-vcard fs-2 text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-white">Editar Biografía</h6>
                                                <p class="mb-0 text-secondary small">Nombre, descripción, contacto y redes</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6">
                                    <a href="admin/habilidades.php" class="text-decoration-none">
                                        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: rgba(25,135,84,0.08); border: 1px solid var(--dark-border);">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-stars fs-2 text-success"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-white">Gestionar Habilidades</h6>
                                                <p class="mb-0 text-secondary small">Tecnologías y herramientas</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6">
                                    <a href="admin/tecnologias.php" class="text-decoration-none">
                                        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: rgba(13,202,240,0.08); border: 1px solid var(--dark-border);">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-cpu fs-2 text-info"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-white">Nivel de Tecnologías</h6>
                                                <p class="mb-0 text-secondary small">Porcentajes de dominio</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6">
                                    <a href="admin/proyectos.php" class="text-decoration-none">
                                        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: rgba(111,66,193,0.08); border: 1px solid var(--dark-border);">
                                            <div class="flex-shrink-0">
                                                <i class="bi bi-folder fs-2" style="color: #a98eda;"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-white">Administrar Proyectos</h6>
                                                <p class="mb-0 text-secondary small">Agregar, editar o eliminar</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensajes recientes -->
                <div class="col-lg-4">
                    <div class="admin-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5><i class="bi bi-envelope me-2"></i>Mensajes Recientes</h5>
                            <a href="admin/mensajes.php" class="text-decoration-none small" style="color: var(--primary-light);">Ver todos</a>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($mensajesRecientes)): ?>
                                <div class="text-center py-4 text-secondary">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    No hay mensajes aún
                                </div>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($mensajesRecientes as $msg): ?>
                                        <div class="list-group-item" style="background: transparent; border-color: rgba(255,255,255,0.03);">
                                            <div class="d-flex w-100 justify-content-between mb-1">
                                                <h6 class="mb-0 text-white" style="font-size: 0.9rem;">
                                                    <?= htmlspecialchars($msg['nombre']) ?>
                                                    <?php if (!$msg['leido']): ?>
                                                        <span class="badge bg-danger" style="font-size: 0.6rem;">Nuevo</span>
                                                    <?php endif; ?>
                                                </h6>
                                                <small class="text-secondary" style="font-size: 0.75rem;">
                                                    <?= date('d/m H:i', strtotime($msg['fecha'])) ?>
                                                </small>
                                            </div>
                                            <p class="mb-1 text-secondary" style="font-size: 0.85rem;">
                                                <strong><?= htmlspecialchars($msg['asunto']) ?></strong>
                                            </p>
                                            <p class="mb-0 text-secondary" style="font-size: 0.8rem; opacity: 0.7;">
                                                <?= htmlspecialchars(substr($msg['mensaje'], 0, 60)) ?><?= strlen($msg['mensaje']) > 60 ? '...' : '' ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
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

        // Auto-dismiss alerts
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert?.close();
            }, 5000);
        });
    </script>
</body>
</html>
