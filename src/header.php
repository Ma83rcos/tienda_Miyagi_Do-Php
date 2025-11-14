<?php
require_once __DIR__ . '/../vendor/autoload.php';
use services\SessionService;

// Obtener la instancia de SessionService
$session = SessionService::getInstance();

// Obtener datos del usuario
$userData = $session->getUser();
$username = $userData['username'] ?? 'Invitado';
$isLoggedIn = $session->isLoggedIn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="tienda-online" content="width=device-width, initial-scale=1.0">
    <title>Tienda Miyagui-Do</title>
    <!--Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">  
    <link rel="icon" href="../imagenes/favicon.png" type="image/png">

</head>
<body>
<!-- Barra de navegacion -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <!--Nombre del protect-->
      <a class="navbar-brand" href="index.php">
      <img src="logo.png" alt="🥋" width="30" height="30" class="d-inline-block align-text-top">
            Tienda Miyagi-Do  
      </a> 
       <!-- Botón toggler para móviles (colapsa el menú en pantallas pequeñas) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
          <!-- Menú principal -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Enlaces a la izquierda -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex gap-4 ms-5">
                <!-- Enlace a la página principal de productos -->
                <li class="nav-item ">
                    <a class="nav-link" href="index.php">Productos</a>
                </li>
            </ul>
              <!-- Usuario y botón Login/Logout a la derecha -->
            <ul class="navbar-nav ms-auto">
                <!-- Nombre de usuario -->
                <li class="nav-item">
                    <span class="navbar-text me-3">
                        <?php 
                         if ($session->isAdmin()) {
                               echo "Administrador";
                            } elseif ($isLoggedIn) {
                               echo htmlspecialchars($username);
                            } else {
                               echo "Invitado";
                            } 
                         ?>
                    </span>
                </li>

                <!-- Botón Login o Logout según el estado de sesión -->
                <li class="nav-item">
                    <?php if ($isLoggedIn): ?>
                        <a class="btn btn-outline-light" href="logout.php">Logout</a>
                    <?php else: ?>
                        <a class="btn btn-outline-light" href="login.php">Login</a>
                    <?php endif; ?>
                </li>
            </ul>

        </div> <!-- fin collapse navbar-collapse -->
    </div> <!-- fin container-fluid -->
</nav>
<!-- =======================================
PASO 5: Scripts de Bootstrap
======================================= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

