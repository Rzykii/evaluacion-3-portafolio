<?php
/**
 * logout.php — Cierre de sesión
 */
require_once 'auth.php';

cerrarSesion();
header('Location: login.php?logout=ok');
exit;
