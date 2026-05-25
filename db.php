<?php
// ============================================================
//  db.php — Configuración de conexión a MySQL
//  Ajusta los valores según tu servidor (XAMPP o hosting)
// ============================================================

// --- XAMPP (local) ---
define('DB_HOST', 'localhost');
define('DB_NAME', 'smunoz_db1');
define('DB_USER', 'smunoz');       // usuario por defecto en XAMPP
define('DB_PASS', 'SmX91mQp#');           // contraseña vacía por defecto en XAMPP
define('DB_CHARSET', 'utf8mb4');

// ⚠️ HOSTING: cambia los valores anteriores por los que te
//    entregue tu proveedor de hosting (cPanel → MySQL Databases)

// --- Crear conexión PDO ---
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
} catch (PDOException $e) {
    // En producción no muestres el error real al usuario
    http_response_code(500);
    die(json_encode(['ok' => false, 'mensaje' => 'Error de conexión con la base de datos.']));
}
