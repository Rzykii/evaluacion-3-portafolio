<?php
/**
 * admin/proyectos.php — CRUD de Proyectos
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

// Eliminar proyecto
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && $idEditar > 0) {
    if (!validarTokenCSRF($_GET['csrf_token'] ?? '')) {
        $mensaje = 'Token de seguridad inválido.';
        $tipoMensaje = 'danger';
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM proyectos WHERE id = ?");
            $stmt->execute([$idEditar]);
            $mensaje = 'Proyecto eliminado correctamente.';
            $tipoMensaje = 'success';
            $modo = 'listar';
            $idEditar = 0;
        } catch (PDOException $e) {
            $mensaje = 'Error al eliminar el proyecto.';
            $tipoMensaje = 'danger';
        }
    }
}

// Cambiar estado
if (isset($_GET['accion']) && $_GET['accion'] === 'toggle' && $idEditar > 0) {
    try {
        $stmt = $pdo->prepare("UPDATE proyectos SET activo = NOT activo WHERE id = ?");
        $stmt->execute([$idEditar]);
        header('Location: proyectos.php');
        exit;
    } catch (PDOException $e) {
        $mensaje = 'Error al cambiar el estado.';
        $tipoMensaje = 'danger';
    }
}

// Guardar proyecto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        $mensaje = 'Token de seguridad inválido.';
        $tipoMensaje = 'danger';
    } else {
        $titulo      = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $imagen      = trim($_POST['imagen'] ?? '');
        $link_demo   = trim($_POST['link_demo'] ?? '');
        $link_codigo = trim($_POST['link_codigo'] ?? '');
        $orden       = (int)($_POST['orden'] ?? 0);
        $activo      = isset($_POST['activo']) ? 1 : 0;

        if (empty($titulo)) {
            $mensaje = 'El título es obligatorio.';
            $tipoMensaje = 'danger';
        } elseif (empty($descripcion)) {
            $mensaje = 'La descripción es obligatoria.';
            $tipoMensaje = 'danger';
        } else {
            try {
                if ($idEditar > 0) {
                    $stmt = $pdo->prepare("UPDATE proyectos SET titulo = ?, descripcion = ?, imagen = ?, link_demo = ?, link_codigo = ?, orden = ?, activo = ? WHERE id = ?");
                    $stmt->execute([$titulo, $descripcion, $imagen, $link_demo, $link_codigo, $orden, $activo, $idEditar]);
                    $mensaje = 'Proyecto actualizado correctamente.';
                } else {
                    $stmt = $pdo->prepare("INSERT INTO proyectos (titulo, descripcion, imagen, link_demo, link_codigo, orden, activo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$titulo, $descripcion, $imagen, $link_demo, $link_codigo, $orden, $activo]);
                    $mensaje = 'Proyecto creado correctamente.';
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
$proyectoEdit = null;
if ($modo === 'editar' && $idEditar > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM proyectos WHERE id = ?");
        $stmt->execute([$idEditar]);
        $proyectoEdit = $stmt->fetch();
        if (!$proyectoEdit) {
            $modo = 'listar';
            $idEditar = 0;
        }
    } catch (PDOException $e) {
        $modo = 'listar';
    }
}

// Obtener todos los proyectos
try {
    $proyectos = $pdo->query("SELECT * FROM proyectos ORDER BY orden, id DESC")->fetchAll();
} catch (PDOException $e) {
    $proyectos = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos — Admin</title>
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
                <a href="proyectos.php" class="nav-link active">
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
                    <h1><i class="bi bi-folder me-2"></i>Proyectos</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Proyectos</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <?php if ($modo === 'listar'): ?>
                        <a href="proyectos.php?modo=crear" class="btn btn-admin btn-admin-primary">
                            <i class="bi bi-plus-lg"></i> Nuevo Proyecto
                        </a>
                    <?php else: ?>
                        <a href="proyectos.php" class="btn btn-admin btn-admin-secondary">
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
                        <h5><?= $modo === 'editar' ? '<i class="bi bi-pencil me-2"></i>Editar' : '<i class="bi bi-plus-lg me-2"></i>Nuevo' ?> Proyecto</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="form-admin">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Título <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="titulo"
                                        value="<?= htmlspecialchars($proyectoEdit['titulo'] ?? '') ?>" required
                                        placeholder="Ej: E-commerce Platform">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Orden</label>
                                    <input type="number" class="form-control" name="orden" min="0"
                                        value="<?= $proyectoEdit['orden'] ?? 0 ?>">
                                    <div class="form-text">Número menor = primero.</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Descripción <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="descripcion" rows="4" required
                                        placeholder="Describe el proyecto..."><?= htmlspecialchars($proyectoEdit['descripcion'] ?? '') ?></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Imagen</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-image"></i></span>
                                        <input type="text" class="form-control" name="imagen"
                                            value="<?= htmlspecialchars($proyectoEdit['imagen'] ?? '') ?>"
                                            placeholder="assets/img/proyecto.jpg">
                                    </div>
                                    <div class="form-text">Ruta relativa de la imagen del proyecto.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Link Demo</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-box-arrow-up-right"></i></span>
                                        <input type="url" class="form-control" name="link_demo"
                                            value="<?= htmlspecialchars($proyectoEdit['link_demo'] ?? '') ?>"
                                            placeholder="https://demo.ejemplo.com">
                                    </div>
                                    <div class="form-text">Dejar vacío si no hay demo disponible.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Link Código (GitHub)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-github"></i></span>
                                        <input type="url" class="form-control" name="link_codigo"
                                            value="<?= htmlspecialchars($proyectoEdit['link_codigo'] ?? '') ?>"
                                            placeholder="https://github.com/usuario/repo">
                                    </div>
                                    <div class="form-text">Dejar vacío si el código es privado.</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="activo" name="activo"
                                            <?= ($proyectoEdit['activo'] ?? 1) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="activo">Activo (visible en el portafolio)</label>
                                    </div>
                                </div>
                                <?php if (!empty($proyectoEdit['imagen'])): ?>
                                    <div class="col-12">
                                        <label class="form-label">Vista previa</label><br>
                                        <img src="../<?= htmlspecialchars($proyectoEdit['imagen']) ?>"
                                            alt="<?= htmlspecialchars($proyectoEdit['titulo'] ?? '') ?>"
                                            class="img-preview">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex gap-2 justify-content-end mt-4 pt-3" style="border-top: 1px solid var(--dark-border);">
                                <a href="proyectos.php" class="btn btn-admin btn-admin-secondary">
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
                <!-- Listado tipo tarjetas -->
                <div class="row g-4">
                    <?php foreach ($proyectos as $proy): ?>
                        <div class="col-md-4">
                            <div class="admin-card h-100">
                                <div class="position-relative">
                                    <?php if (!empty($proy['imagen'])): ?>
                                        <img src="../<?= htmlspecialchars($proy['imagen']) ?>"
                                            alt="<?= htmlspecialchars($proy['titulo']) ?>"
                                            style="width: 100%; height: 160px; object-fit: cover; border-radius: 1rem 1rem 0 0;">
                                    <?php else: ?>
                                        <div style="width: 100%; height: 160px; background: rgba(13,110,253,0.08);
                                            display: flex; align-items: center; justify-content: center; border-radius: 1rem 1rem 0 0;">
                                            <i class="bi bi-folder fs-1 text-primary opacity-50"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge-estado <?= $proy['activo'] ? 'activo' : 'inactivo' ?>" style="font-size: 0.65rem;">
                                            <?= $proy['activo'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6 class="text-white mb-2"><?= htmlspecialchars($proy['titulo']) ?></h6>
                                    <p class="text-secondary" style="font-size: 0.85rem; line-height: 1.5;">
                                        <?= htmlspecialchars(substr($proy['descripcion'], 0, 120)) ?><?= strlen($proy['descripcion']) > 120 ? '...' : '' ?>
                                    </p>
                                    <div class="d-flex gap-1 mb-3">
                                        <?php if (!empty($proy['link_demo'])): ?>
                                            <a href="<?= htmlspecialchars($proy['link_demo']) ?>" target="_blank" class="badge text-decoration-none" style="background: rgba(13,110,253,0.15); color: #6ea8fe;">
                                                <i class="bi bi-box-arrow-up-right me-1"></i>Demo
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($proy['link_codigo'])): ?>
                                            <a href="<?= htmlspecialchars($proy['link_codigo']) ?>" target="_blank" class="badge text-decoration-none" style="background: rgba(255,255,255,0.1); color: #9aa7c2;">
                                                <i class="bi bi-github me-1"></i>Código
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex gap-1 justify-content-end pt-2" style="border-top: 1px solid var(--dark-border);">
                                        <a href="proyectos.php?accion=toggle&id=<?= $proy['id'] ?>"
                                            class="btn-action view" title="Cambiar estado">
                                            <i class="bi bi-toggle-<?= $proy['activo'] ? 'on' : 'off' ?>"></i>
                                        </a>
                                        <a href="proyectos.php?modo=editar&id=<?= $proy['id'] ?>"
                                            class="btn-action edit" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="proyectos.php?accion=eliminar&id=<?= $proy['id'] ?>&csrf_token=<?= urlencode($csrfToken) ?>"
                                            class="btn-action delete" title="Eliminar"
                                            onclick="return confirm('¿Eliminar el proyecto <?= htmlspecialchars($proy['titulo']) ?>?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($proyectos)): ?>
                        <div class="col-12">
                            <div class="admin-card">
                                <div class="card-body text-center py-5 text-secondary">
                                    <i class="bi bi-folder fs-1 d-block mb-2 opacity-50"></i>
                                    No hay proyectos registrados.<br>
                                    <a href="proyectos.php?modo=crear" class="btn btn-sm btn-primary mt-2">
                                        <i class="bi bi-plus-lg"></i> Agregar primer proyecto
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
