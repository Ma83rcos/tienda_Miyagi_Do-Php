<?php
// ============================================
//  Cierre de sesión - logout.php
// ============================================
require_once __DIR__ .'/../vendor/autoload.php';

use services\SessionService;

// Iniciamos el servicio de sesión
$session = SessionService::getInstance();

// Cerramos la sesión de usuario
$session->logout();

// Redirigimos al inicio (página principal)
header('Location: index.php');
exit;
?>
