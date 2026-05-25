<?php
// ============================================================
//  enviar_contacto.php — Recibe el formulario de contacto
//  El JS hace un fetch() POST a este archivo
// ============================================================

header('Content-Type: application/json; charset=utf-8');

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'mensaje' => 'Método no permitido.']);
    exit;
}

require_once 'db.php';

// --- Leer y sanear datos ---
$nombre  = trim($_POST['nombre']  ?? '');
$email   = trim($_POST['email']   ?? '');
$asunto  = trim($_POST['asunto']  ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

// --- Validaciones del lado del servidor ---
$errores = [];

if (mb_strlen($nombre) < 3) {
    $errores[] = 'El nombre debe tener al menos 3 caracteres.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'El correo electrónico no es válido.';
}
if (mb_strlen($asunto) < 5) {
    $errores[] = 'El asunto debe tener al menos 5 caracteres.';
}
if (mb_strlen($mensaje) < 10) {
    $errores[] = 'El mensaje debe tener al menos 10 caracteres.';
}

if (!empty($errores)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'errores' => $errores]);
    exit;
}

// --- Guardar en la base de datos ---
try {
    $stmt = $pdo->prepare(
        "INSERT INTO contacto (nombre, email, asunto, mensaje)
         VALUES (:nombre, :email, :asunto, :mensaje)"
    );
    $stmt->execute([
        ':nombre'  => htmlspecialchars($nombre,  ENT_QUOTES, 'UTF-8'),
        ':email'   => $email,
        ':asunto'  => htmlspecialchars($asunto,  ENT_QUOTES, 'UTF-8'),
        ':mensaje' => htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'),
    ]);

    echo json_encode(['ok' => true, 'mensaje' => '¡Mensaje recibido! Me pondré en contacto pronto.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'mensaje' => 'No se pudo guardar el mensaje. Inténtalo más tarde.']);
}
