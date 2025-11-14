<?php

use services\CategoriaService;
use services\ProductoService;

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/services/ProductoService.php';
require_once __DIR__ . '/services/CategoriaService.php';
require_once __DIR__ . '/models/Producto.php';
require_once __DIR__ . '/models/Categoria.php';
require_once __DIR__ . '/config/Config.php';

//Instanciamos los servicios necesarios
$productoService = new ProductoService();
$categoriaService = new CategoriaService();

//Mandamos cabecera
require_once "header.php";

//Recepción y validación de parámetros
$productoId = $_GET['id'];
// Obtenemos el producto de la base de datos
$producto = $productoService->findById($productoId);

if (!$producto) {
    // Producto no encontrado, redirigimos al listado
    header('Location: productos.php?error=not_found');
    exit;
}

// Obtenemos la categoría del producto
$categoria = $categoriaService->findById($producto->getCategoriaId());

?>

<div class="container mt-4">
    <h2>Detalles del producto</h2>

    <div class="card mb-3" style="max-width: 600px;">
        <div class="row g-0">
            <?php if ($producto->getImagen()): ?>
                <div class="col-md-4">
                    <img src="uploads/<?= htmlspecialchars($producto->getImagen()) ?>" class="img-fluid rounded-start" alt="Imagen de <?= htmlspecialchars($producto->getMarca() . ' ' . $producto->getModelo()) ?>">
                </div>
            <?php endif; ?>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($producto->getMarca() . ' ' . $producto->getModelo()) ?></h5>
                    <p class="card-text"><strong>ID:</strong> <?= $producto->getId() ?></p>
                    <p class="card-text"><strong>Descripción:</strong> <?= htmlspecialchars($producto->getDescripcion()) ?></p>
                    <p class="card-text"><strong>Precio:</strong><?= number_format($producto->getPrecio(), 2) ?> €</p>
                    <p class="card-text"><strong>Stock:</strong> <?= $producto->getStock() ?></p>
                    <p class="card-text"><strong>Color:</strong> <?= htmlspecialchars($producto->getColor()) ?></p>
                    <p class="card-text"><strong>Talla:</strong> <?= htmlspecialchars($producto->getTalla()) ?></p>
                    <p class="card-text"><strong>Categoría:</strong> <?= $categoria ? htmlspecialchars($categoria->getNombre()) : 'N/A' ?></p>
                </div>
            </div>
        </div>
    </div>

    <a href="index.php" class="btn btn-secondary">Volver al listado</a>
</div>

<?php require_once "footer.php"; ?>