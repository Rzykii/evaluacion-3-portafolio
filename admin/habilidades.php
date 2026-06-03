<?php
/**
 * admin/habilidades.php — CRUD de Habilidades
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

// Eliminar habilidad
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && $idEditar > 0) {
    if (!validarTokenCSRF($_GET['csrf_token'] ?? '')) {
        $mensaje = 'Token de seguridad inválido.';
        $tipoMensaje = 'danger';
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM habilidades WHERE id = ?");
            $stmt->execute([$idEditar]);
            $mensaje = 'Habilidad eliminada correctamente.';
            $tipoMensaje = 'success';
            $modo = 'listar';
            $idEditar = 0;
        } catch (PDOException $e) {
            $mensaje = 'Error al eliminar la habilidad.';
            $tipoMensaje = 'danger';
        }
    }
}

// Cambiar estado (activar/desactivar)
if (isset($_GET['accion']) && $_GET['accion'] === 'toggle' && $idEditar > 0) {
    try {
        $stmt = $pdo->prepare("UPDATE habilidades SET activo = NOT activo WHERE id = ?");
        $stmt->execute([$idEditar]);
        header('Location: habilidades.php');
        exit;
    } catch (PDOException $e) {
        $mensaje = 'Error al cambiar el estado.';
        $tipoMensaje = 'danger';
    }
}

// Guardar habilidad (crear o actualizar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        $mensaje = 'Token de seguridad inválido.';
        $tipoMensaje = 'danger';
    } else {
        $nombre    = trim($_POST['nombre'] ?? '');
        $categoria = $_POST['categoria'] ?? 'Frontend';
        $icono     = trim($_POST['icono'] ?? '');
        $orden     = (int)($_POST['orden'] ?? 0);
        $activo    = isset($_POST['activo']) ? 1 : 0;

        if (empty($nombre)) {
            $mensaje = 'El nombre es obligatorio.';
            $tipoMensaje = 'danger';
        } else {
            try {
                if ($idEditar > 0) {
                    // Actualizar
                    $stmt = $pdo->prepare("UPDATE habilidades SET nombre = ?, categoria = ?, icono = ?, orden = ?, activo = ? WHERE id = ?");
                    $stmt->execute([$nombre, $categoria, $icono, $orden, $activo, $idEditar]);
                    $mensaje = 'Habilidad actualizada correctamente.';
                } else {
                    // Crear
                    $stmt = $pdo->prepare("INSERT INTO habilidades (nombre, categoria, icono, orden, activo) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$nombre, $categoria, $icono, $orden, $activo]);
                    $mensaje = 'Habilidad creada correctamente.';
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
$habilidadEdit = null;
if ($modo === 'editar' && $idEditar > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM habilidades WHERE id = ?");
        $stmt->execute([$idEditar]);
        $habilidadEdit = $stmt->fetch();
        if (!$habilidadEdit) {
            $modo = 'listar';
            $idEditar = 0;
        }
    } catch (PDOException $e) {
        $modo = 'listar';
    }
}

// Obtener todas las habilidades
try {
    $habilidades = $pdo->query("SELECT * FROM habilidades ORDER BY orden, id DESC")->fetchAll();
} catch (PDOException $e) {
    $habilidades = [];
}

$categorias = ['Frontend', 'Backend', 'Database', 'Tools', 'Otra'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habilidades — Admin</title>
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
                <a href="habilidades.php" class="nav-link active">
                    <i class="bi bi-stars"></i><span>Habilidades</span>
                </a>
                <a href="tecnologias.php" class="nav-link">
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
                    <h1><i class="bi bi-stars me-2"></i>Habilidades</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Habilidades</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <?php if ($modo === 'listar'): ?>
                        <a href="habilidades.php?modo=crear" class="btn btn-admin btn-admin-primary">
                            <i class="bi bi-plus-lg"></i> Nueva Habilidad
                        </a>
                    <?php else: ?>
                        <a href="habilidades.php" class="btn btn-admin btn-admin-secondary">
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
                        <h5><?= $modo === 'editar' ? '<i class="bi bi-pencil me-2"></i>Editar' : '<i class="bi bi-plus-lg me-2"></i>Nueva' ?> Habilidad</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="form-admin">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nombre"
                                        value="<?= htmlspecialchars($habilidadEdit['nombre'] ?? '') ?>" required
                                        placeholder="Ej: HTML, JavaScript, PHP">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Categoría <span class="text-danger">*</span></label>
                                    <select class="form-select" name="categoria" required>
                                        <?php foreach ($categorias as $cat): ?>
                                            <option value="<?= $cat ?>" <?= ($habilidadEdit['categoria'] ?? '') === $cat ? 'selected' : '' ?>>
                                                <?= $cat ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Icono / Imagen</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-image"></i></span>
                                        <input type="text" class="form-control" name="icono"
                                            value="<?= htmlspecialchars($habilidadEdit['icono'] ?? '') ?>"
                                            placeholder="assets/img/html.png">
                                    </div>
                                    <div class="form-text">Ruta relativa de la imagen del icono.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Orden</label>
                                    <input type="number" class="form-control" name="orden" min="0"
                                        value="<?= $habilidadEdit['orden'] ?? 0 ?>">
                                    <div class="form-text">Número menor = primero.</div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="activo" name="activo"
                                            <?= ($habilidadEdit['activo'] ?? 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="activo">Activo (visible en el portafolio)</label>
                                    </div>
                                </div>
                                <?php if (!empty($habilidadEdit['icono'])): ?>
                                    <div class="col-12">
                                        <label class="form-label">Vista previa</label><br>
                                        <img src="../<?= htmlspecialchars($habilidadEdit['icono']) ?>"
                                            alt="<?= htmlspecialchars($habilidadEdit['nombre'] ?? '') ?>"
                                            style="width: 50px; height: 50px; object-fit: contain; border-radius: 0.5rem;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex gap-2 justify-content-end mt-4 pt-3" style="border-top: 1px solid var(--dark-border);">
                                <a href="habilidades.php" class="btn btn-admin btn-admin-secondary">
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
                <div class="admin-card">
                    <div class="card-header">
                        <h5>Listado de Habilidades</h5>
                        <span class="text-secondary" style="font-size: 0.85rem;"><?= count($habilidades) ?> registros</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($habilidades)): ?>
                            <div class="text-center py-5 text-secondary">
                                <i class="bi bi-stars fs-1 d-block mb-2 opacity-50"></i>
                                No hay habilidades registradas.<br>
                                <a href="habilidades.php?modo=crear" class="btn btn-sm btn-primary mt-2">
                                    <i class="bi bi-plus-lg"></i> Agregar primera habilidad
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">Orden</th>
                                            <th style="width: 60px;">Icono</th>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>Estado</th>
                                            <th style="width: 140px;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($habilidades as $hab): ?>
                                            <tr>
                                                <td><?= $hab['orden'] ?></td>
                                                <td>
                                                    <?php if (!empty($hab['icono'])): ?>
                                                        <img src="../<?= htmlspecialchars($hab['icono']) ?>"
                                                            alt="" style="width: 35px; height: 35px; object-fit: contain; border-radius: 0.35rem;">
                                                    <?php else: ?>
                                                        <div style="width: 35px; height: 35px; background: rgba(13,110,253,0.1); border-radius: 0.35rem;
                                                            display: flex; align-items: center; justify-content: center;">
                                                            <i class="bi bi-code-slash text-primary" style="font-size: 0.8rem;"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="fw-medium text-white"><?= htmlspecialchars($hab['nombre']) ?></td>
                                                <td>
                                                    <span class="badge rounded-pill"
                                                        style="background: rgba(13,110,253,0.1); color: #6ea8fe; border: 1px solid rgba(13,110,253,0.2); font-weight: 500;">
                                                        <?= $hab['categoria'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge-estado <?= $hab['activo'] ? 'activo' : 'inactivo' ?>">
                                                        <?= $hab['activo'] ? 'Activo' : 'Inactivo' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="habilidades.php?accion=toggle&id=<?= $hab['id'] ?>"
                                                        class="btn-action view" title="Cambiar estado">
                                                        <i class="bi bi-toggle-<?= $hab['activo'] ? 'on' : 'off' ?>"></i>
                                                    </a>
                                                    <a href="habilidades.php?modo=editar&id=<?= $hab['id'] ?>"
                                                        class="btn-action edit" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="habilidades.php?accion=eliminar&id=<?= $hab['id'] ?>&csrf_token=<?= urlencode($csrfToken) ?>"
                                                        class="btn-action delete" title="Eliminar"
                                                        onclick="return confirm('¿Eliminar <?= htmlspecialchars($hab['nombre']) ?>? Esta acción no se puede deshacer.')">
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
