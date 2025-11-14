<?php

use services\ProductoService;
use services\SessionService;
require_once __DIR__ . '/../vendor/autoload.php';

//Control de acceso
$session = SessionService::getInstance();
//Solo el administardor puede acceder
if (!$session->isAdmin()) {
    header('Location: index.php');
    exit;
}
//Mandamos cabecera
require_once "header.php";

//Iniciamos servicio productos
$productoService = new ProductoService();

// Comprobamos que exista el parámetro id en la URL
if(!isset($_GET['id']) || empty($_GET['id'])){
    header('Location:  productos.php');
    exit;
}

//Si existe 
$productoId = $_GET['id'];
// Obtenemos el producto de la base de datos
$producto = $productoService->findById($productoId);
//Si no existe
if(!$producto){
    header('Location: productos.php?error=not_found');
    exit;
}
?>
<div class="container mt-5">
    <h2>Actualizar imagen del producto</h2>

    <div class="card mt-3 p-3">
        <p><strong>ID:</strong> <?php echo $producto->getId(); ?></p>
        <p><strong>Marca:</strong> <?php echo htmlspecialchars($producto->getMarca()); ?></p>
        <p><strong>Modelo:</strong> <?php echo htmlspecialchars($producto->getModelo()); ?></p>
        <p><strong>Imagen actual:</strong></p>
        <?php if ($producto->getImagen()): ?>
            <img src="uploads/<?php echo $producto->getImagen(); ?>" alt="Imagen del producto" class="img-thumbnail" width="200">
        <?php else: ?>
            <p>No hay imagen disponible.</p>
        <?php endif; ?>

        <form action="update_image_file.php" method="POST" enctype="multipart/form-data" class="mt-3">
            <input type="hidden" name="id" value="<?php echo $producto->getId(); ?>">
            <div class="mb-3">
                <label for="imagen" class="form-label">Seleccionar nueva imagen</label>
                <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar imagen</button>
            <a href="index.php" class="btn btn-secondary">Volver al listado</a>
        </form>
    </div>
</div>

<?php
// Pie de pagina
require_once "footer.php";
?>