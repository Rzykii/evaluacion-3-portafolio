<?php
/**
 * admin/biografia.php — CRUD de Biografía
 */
require_once '../auth.php';
verificarSesion();
require_once '../db.php';

$usuario = obtenerUsuarioActual();
$csrfToken = generarTokenCSRF();

$mensaje = '';
$tipoMensaje = '';

// Actualizar biografía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validarTokenCSRF($_POST['csrf_token'] ?? '')) {
        $mensaje = 'Token de seguridad inválido.';
        $tipoMensaje = 'danger';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE biografia SET valor = ? WHERE campo = ?");

            $campos = [
                'nombre', 'titulo', 'descripcion', 'email',
                'telefono', 'ubicacion', 'github', 'linkedin',
                'twitter', 'instagram', 'foto_perfil'
            ];

            foreach ($campos as $campo) {
                $valor = $_POST[$campo] ?? '';
                $stmt->execute([$valor, $campo]);
            }

            $mensaje = 'Biografía actualizada correctamente.';
            $tipoMensaje = 'success';
        } catch (PDOException $e) {
            error_log('Error actualizando biografía: ' . $e->getMessage());
            $mensaje = 'Error al guardar los cambios.';
            $tipoMensaje = 'danger';
        }
    }
}

// Obtener datos actuales
try {
    $stmt = $pdo->query("SELECT campo, valor, tipo, etiqueta FROM biografia ORDER BY orden");
    $biografia = [];
    while ($row = $stmt->fetch()) {
        $biografia[$row['campo']] = $row;
    }
} catch (PDOException $e) {
    $biografia = [];
    $mensaje = 'Error al cargar la biografía.';
    $tipoMensaje = 'danger';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biografía — Admin</title>
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
                <a href="biografia.php" class="nav-link active">
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
                    <h1><i class="bi bi-person-vcard me-2"></i>Biografía</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Biografía</li>
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

            <div class="admin-card">
                <div class="card-header">
                    <h5><i class="bi bi-pencil-square me-2"></i>Editar Información Personal</h5>
                    <a href="../index.php#Biografía" target="_blank" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-eye me-1"></i>Ver en portafolio
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" class="form-admin">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                        <!-- Información principal -->
                        <h6 class="text-primary mb-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="bi bi-info-circle me-1"></i>Información Principal
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombre"
                                    value="<?= htmlspecialchars($biografia['nombre']['valor'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Título profesional <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="titulo"
                                    value="<?= htmlspecialchars($biografia['titulo']['valor'] ?? '') ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción personal <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="descripcion" rows="4" required><?= htmlspecialchars($biografia['descripcion']['valor'] ?? '') ?></textarea>
                                <div class="form-text">Esta descripción aparece en la sección About/Biografía.</div>
                            </div>
                        </div>

                        <!-- Contacto -->
                        <h6 class="text-primary mb-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="bi bi-telephone me-1"></i>Información de Contacto
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Correo electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" name="email"
                                        value="<?= htmlspecialchars($biografia['email']['valor'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control" name="telefono"
                                        value="<?= htmlspecialchars($biografia['telefono']['valor'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ubicación</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control" name="ubicacion"
                                        value="<?= htmlspecialchars($biografia['ubicacion']['valor'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Redes Sociales -->
                        <h6 class="text-primary mb-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="bi bi-share me-1"></i>Redes Sociales
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">GitHub</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-github"></i></span>
                                    <input type="url" class="form-control" name="github"
                                        value="<?= htmlspecialchars($biografia['github']['valor'] ?? '') ?>"
                                        placeholder="https://github.com/usuario">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">LinkedIn</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-linkedin"></i></span>
                                    <input type="url" class="form-control" name="linkedin"
                                        value="<?= htmlspecialchars($biografia['linkedin']['valor'] ?? '') ?>"
                                        placeholder="https://linkedin.com/in/usuario">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Twitter / X</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-twitter-x"></i></span>
                                    <input type="url" class="form-control" name="twitter"
                                        value="<?= htmlspecialchars($biografia['twitter']['valor'] ?? '') ?>"
                                        placeholder="https://twitter.com/usuario">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Instagram</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                                    <input type="url" class="form-control" name="instagram"
                                        value="<?= htmlspecialchars($biografia['instagram']['valor'] ?? '') ?>"
                                        placeholder="https://instagram.com/usuario">
                                </div>
                            </div>
                        </div>

                        <!-- Foto de perfil -->
                        <h6 class="text-primary mb-3" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="bi bi-image me-1"></i>Foto de Perfil
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label">URL de la imagen</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-link"></i></span>
                                    <input type="text" class="form-control" name="foto_perfil"
                                        value="<?= htmlspecialchars($biografia['foto_perfil']['valor'] ?? '') ?>"
                                        placeholder="assets/img/foto.png">
                                </div>
                                <div class="form-text">Ruta relativa desde la raíz del proyecto.</div>
                            </div>
                            <div class="col-md-4">
                                <?php if (!empty($biografia['foto_perfil']['valor'])): ?>
                                    <label class="form-label">Vista previa</label><br>
                                    <img src="../<?= htmlspecialchars($biografia['foto_perfil']['valor']) ?>"
                                        alt="Foto de perfil" class="img-preview">
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2 justify-content-end pt-3" style="border-top: 1px solid var(--dark-border);">
                            <a href="../dashboard.php" class="btn btn-admin btn-admin-secondary">
                                <i class="bi bi-x-lg"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-admin btn-admin-primary">
                                <i class="bi bi-check-lg"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
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
