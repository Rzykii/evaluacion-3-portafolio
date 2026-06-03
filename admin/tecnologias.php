<?php
/**
 * admin/tecnologias.php — CRUD de Tecnologías con porcentaje de dominio
 */
require_once '../auth.php';
verificarSesion();
require_once '../db.php';

$usuario = obtenerUsuarioActual();
$csrfToken = generarTokenCSRF();

$modo = $_GET['modo'] ?? 'listar';
$idEditar = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$mensaje = '';
$tipoMensaje = '';

// Eliminar tecnología
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && $idEditar > 0) {
    if (!validarTokenCSRF($_GET['csrf_token'] ?? '')) {
        $mensaje = 'Token de seguridad inválido.';
        $tipoMensaje = 'danger';
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM tecnologias WHERE id = ?");
            $stmt->execute([$idEditar]);
            $mensaje = 'Tecnología eliminada correctamente.';
            $tipoMensaje = 'success';
            $modo = 'listar';
            $idEditar = 0;
        } catch (PDOException $e) {
            $mensaje = 'Error al eliminar la tecnología.';
            $tipoMensaje = 'danger';
        }
    }
}

// Cambiar estado
if (isset($_GET['accion']) && $_GET['accion'] === 'toggle' && $idEditar > 0) {
    try {
        $stmt = $pdo->prepare("UPDATE tecnologias SET activo = NOT activo WHERE id = ?");
        $stmt->execute([$idEditar]);
        header('Location: tecnologias.php');
        exit;
    } catch (PDOException $e) {
        $mensaje = 'Error al cambiar el estado.';
        $tipoMensaje = 'danger';
    }
}

// Guardar tecnología
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        $mensaje = 'Token de seguridad inválido.';
        $tipoMensaje = 'danger';
    } else {
        $nombre     = trim($_POST['nombre'] ?? '');
        $categoria  = $_POST['categoria'] ?? 'Frontend';
        $porcentaje = (int)($_POST['porcentaje'] ?? 0);
        $color      = $_POST['color'] ?? 'primary';
        $emoji      = trim($_POST['emoji'] ?? '');
        $orden      = (int)($_POST['orden'] ?? 0);
        $activo     = isset($_POST['activo']) ? 1 : 0;

        if (empty($nombre)) {
            $mensaje = 'El nombre es obligatorio.';
            $tipoMensaje = 'danger';
        } elseif ($porcentaje < 0 || $porcentaje > 100) {
            $mensaje = 'El porcentaje debe estar entre 0 y 100.';
            $tipoMensaje = 'danger';
        } else {
            try {
                if ($idEditar > 0) {
                    $stmt = $pdo->prepare("UPDATE tecnologias SET nombre = ?, categoria = ?, porcentaje = ?, color = ?, emoji = ?, orden = ?, activo = ? WHERE id = ?");
                    $stmt->execute([$nombre, $categoria, $porcentaje, $color, $emoji, $orden, $activo, $idEditar]);
                    $mensaje = 'Tecnología actualizada correctamente.';
                } else {
                    $stmt = $pdo->prepare("INSERT INTO tecnologias (nombre, categoria, porcentaje, color, emoji, orden, activo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$nombre, $categoria, $porcentaje, $color, $emoji, $orden, $activo]);
                    $mensaje = 'Tecnología creada correctamente.';
                }
                $tipoMensaje = 'success';
                $modo = 'listar';
                $idEditar = 0;
            } catch (PDOException $e) {
                $mensaje = 'Error al guardar: ' . $e->getMessage();
                $tipoMensaje = 'danger';
            }
        }
    }
}

// Obtener datos para editar
$tecnologiaEdit = null;
if ($modo === 'editar' && $idEditar > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM tecnologias WHERE id = ?");
        $stmt->execute([$idEditar]);
        $tecnologiaEdit = $stmt->fetch();
        if (!$tecnologiaEdit) {
            $modo = 'listar';
            $idEditar = 0;
        }
    } catch (PDOException $e) {
        $modo = 'listar';
    }
}

// Obtener todas las tecnologías
try {
    $tecnologias = $pdo->query("SELECT * FROM tecnologias ORDER BY orden, id DESC")->fetchAll();
} catch (PDOException $e) {
    $tecnologias = [];
}

$categorias = ['Frontend', 'Backend', 'Database', 'Tools'];
$colores = [
    'primary' => 'Azul',
    'success' => 'Verde',
    'info'    => 'Cyan',
    'warning' => 'Amarillo',
    'danger'  => 'Rojo',
    'dark'    => 'Oscuro'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tecnologías — Admin</title>
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
                <a href="tecnologias.php" class="nav-link active">
                    <i class="bi bi-cpu"></i><span>Tecnologías</span>
                </a>
                <a href="proyectos.php" class="nav-link">
                    <i class="bi bi-folder"></i><span>Proyectos</span>
                </a>

                <div class="nav-section mt-3">Contacto</div>
                <a href="mensajes.php" class="nav-link">
                    <i class="bi bi-envelope"></i><span>Mensajes</span>
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
                    <h1><i class="bi bi-cpu me-2"></i>Tecnologías</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Tecnologías</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <?php if ($modo === 'listar'): ?>
                        <a href="tecnologias.php?modo=crear" class="btn btn-admin btn-admin-primary">
                            <i class="bi bi-plus-lg"></i> Nueva Tecnología
                        </a>
                    <?php else: ?>
                        <a href="tecnologias.php" class="btn btn-admin btn-admin-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    <?php endif; ?>
                    <button class="btn btn-outline-light d-lg-none" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </div>

            <?php if ($mensaje): ?>
                <?= mostrarAlertaBootstrap($tipoMensaje, $mensaje) ?>
            <?php endif; ?>

            <?php if ($modo === 'crear' || $modo === 'editar'): ?>
                <!-- Formulario -->
                <div class="admin-card">
                    <div class="card-header">
                        <h5><?= $modo === 'editar' ? '<i class="bi bi-pencil me-2"></i>Editar' : '<i class="bi bi-plus-lg me-2"></i>Nueva' ?> Tecnología</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="form-admin">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nombre"
                                        value="<?= htmlspecialchars($tecnologiaEdit['nombre'] ?? '') ?>" required
                                        placeholder="Ej: HTML, PHP, MySQL">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Categoría <span class="text-danger">*</span></label>
                                    <select class="form-select" name="categoria" required>
                                        <?php foreach ($categorias as $cat): ?>
                                            <option value="<?= $cat ?>" <?= ($tecnologiaEdit['categoria'] ?? '') === $cat ? 'selected' : '' ?>>
                                                <?= $cat ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Porcentaje de dominio (0-100) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="porcentaje" min="0" max="100"
                                            value="<?= $tecnologiaEdit['porcentaje'] ?? 80 ?>" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Color de la barra</label>
                                    <select class="form-select" name="color">
                                        <?php foreach ($colores as $val => $label): ?>
                                            <option value="<?= $val ?>" <?= ($tecnologiaEdit['color'] ?? 'primary') === $val ? 'selected' : '' ?>>
                                                <?= $label ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Emoji</label>
                                    <input type="text" class="form-control" name="emoji"
                                        value="<?= htmlspecialchars($tecnologiaEdit['emoji'] ?? '') ?>"
                                        placeholder="🌐 🎨 ⚡" maxlength="10">
                                    <div class="form-text">Emoji que aparece junto al nombre.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Orden</label>
                                    <input type="number" class="form-control" name="orden" min="0"
                                        value="<?= $tecnologiaEdit['orden'] ?? 0 ?>">
                                </div>
                                <div class="col-md-8 d-flex align-items-end">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="activo" name="activo"
                                            <?= ($tecnologiaEdit['activo'] ?? 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="activo">Activo (visible en el portafolio)</label>
                                    </div>
                                </div>
                                <!-- Preview -->
                                <div class="col-12">
                                    <label class="form-label">Vista previa de la barra</label>
                                    <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid var(--dark-border);">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>
                                                <?= !empty($tecnologiaEdit['emoji']) ? $tecnologiaEdit['emoji'] . ' ' : '' ?>
                                                <?= htmlspecialchars($tecnologiaEdit['nombre'] ?? 'Ejemplo') ?>
                                            </span>
                                            <span><?= $tecnologiaEdit['porcentaje'] ?? 80 ?>%</span>
                                        </div>
                                        <div class="progress-admin">
                                            <div class="progress-bar bg-<?= $tecnologiaEdit['color'] ?? 'primary' ?>"
                                                style="width: <?= $tecnologiaEdit['porcentaje'] ?? 80 ?>%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 justify-content-end mt-4 pt-3" style="border-top: 1px solid var(--dark-border);">
                                <a href="tecnologias.php" class="btn btn-admin btn-admin-secondary">
                                    <i class="bi bi-x-lg"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-admin btn-admin-primary">
                                    <i class="bi bi-check-lg"></i> Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            <?php else: ?>
                <!-- Listado -->
                <div class="row g-4">
                    <?php foreach ($tecnologias as $tec): ?>
                        <div class="col-md-6">
                            <div class="admin-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="font-size: 1.2rem;"><?= $tec['emoji'] ?? '' ?></span>
                                            <h6 class="mb-0 text-white"><?= htmlspecialchars($tec['nombre']) ?></h6>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <a href="tecnologias.php?modo=editar&id=<?= $tec['id'] ?>" class="btn-action edit" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="tecnologias.php?accion=eliminar&id=<?= $tec['id'] ?>&csrf_token=<?= urlencode($csrfToken) ?>"
                                                class="btn-action delete" title="Eliminar"
                                                onclick="return confirm('¿Eliminar <?= htmlspecialchars($tec['nombre']) ?>?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2" style="font-size: 0.85rem;">
                                        <span class="text-secondary"><?= $tec['categoria'] ?></span>
                                        <span class="text-white"><?= $tec['porcentaje'] ?>%</span>
                                    </div>

                                    <div class="progress-admin mb-3">
                                        <div class="progress-bar bg-<?= $tec['color'] ?>" style="width: <?= $tec['porcentaje'] ?>%;"></div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge-estado <?= $tec['activo'] ? 'activo' : 'inactivo' ?>" style="font-size: 0.7rem;">
                                            <?= $tec['activo'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                        <a href="tecnologias.php?accion=toggle&id=<?= $tec['id'] ?>"
                                            class="text-decoration-none" style="font-size: 0.8rem; color: var(--primary-light);">
                                            <?= $tec['activo'] ? 'Desactivar' : 'Activar' ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($tecnologias)): ?>
                        <div class="col-12">
                            <div class="admin-card">
                                <div class="card-body text-center py-5 text-secondary">
                                    <i class="bi bi-cpu fs-1 d-block mb-2 opacity-50"></i>
                                    No hay tecnologías registradas.<br>
                                    <a href="tecnologias.php?modo=crear" class="btn btn-sm btn-primary mt-2">
                                        <i class="bi bi-plus-lg"></i> Agregar primera tecnología
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
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
