<?php
use services\SessionService;
use config\Config;
use services\CategoriaService;
use services\ProductoService;

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/services/ProductoService.php';
require_once __DIR__ . '/services/CategoriaService.php';
require_once __DIR__ . '/models/Producto.php';
require_once __DIR__ . '/models/Categoria.php';
require_once __DIR__ . '/config/Config.php';

//Iniciamos el servicio de sesion 
$session = SessionService::getInstance();
//Instanciamos clases utilizadas
$productoService =  new ProductoService();
$categoriaService = new CategoriaService();
//Config ya Instanciada
$config = Config::getInstance();

//Control de acceso segun roles si no es administrador
if (!$session->isAdmin()) {
    header('Location: index.php');
    exit;//Buena practica despues de header
}

//Recepcion y validacion del parametro
$productoId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($productoId <= 0) {
    header('Location: productos.php?error=id_invalido');
    exit;
}

//Ahora $productoId es seguro para usar
$producto = $productoService->findById($productoId);
if(!$producto){
    header('Location: productos.php?error=not_found');
    exit;
}

// --- Eliminación de la imagen asociada ---
if (!empty($producto->getImagen()) && $producto->getImagen() !== 'default.png') {
    $rutaImagen = __DIR__ . '/imagenes/productos/' . $producto->getImagen();

    if (file_exists($rutaImagen)) {
        unlink($rutaImagen); // elimina el archivo físicamente
    }
}

// --- Eliminación del producto ---
if ($productoService->deleteById($productoId)) {
    header('Location: index.php?success=eliminado');
} else {
    header('Location: index.php?error=fallo_eliminacion');
}
exit;

?>