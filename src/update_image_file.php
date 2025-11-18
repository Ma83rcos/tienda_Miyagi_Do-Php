<?php

use services\SessionService;
use services\ProductoService;

require_once __DIR__ . '/../vendor/autoload.php';

//Iniciamos sesion y servicios
$session = SessionService::getInstance();

//Solo el administardor puede acceder
if (!$session->isAdmin()) {
    header('Location: index.php');
    exit;
}

// Inicializamos servicio de productos
$productoService = new ProductoService();

// Comprobamos que exista el parámetro id en la URL
if(!isset($_POST['id']) || empty($_POST['id'])){
    header('Location:  productos.php');
    exit;
}

$productoId = $_POST['id'];

// Obtenemos el producto de la base de datos
$producto = $productoService->findById($productoId);

if(!$producto){
    header('Location: productos.php?error=not_found');
    exit;
}

// Validamos que se haya enviado un archivo
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK){
    header('Location: update-image.php?id=' . $productoId . '&error=subida_fallida');
    exit;
}

$file = $_FILES['imagen'];

//Tipos de extensiones permitidas subidas de imagenes
$allowedTypes = ['image/jpeg', 'image/png'];
$allowedExts = ['jpg', 'jpeg', 'png'];


$fileType = mime_content_type($file['tmp_name']);
$fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

//Validacion de tipo Mine y extension
if (!in_array($fileType, $allowedTypes) || !in_array($fileExt, $allowedExts)) {
    header('Location: update-image.php?id=' . $productoId . '&error=tipo_no_valido');
    exit;
}

// Carpeta de uploads
$uploadDir = __DIR__ . '/uploads/';

//Nombre del archivo 
$newFileName = 'producto_' . $producto->getId() . '_' . time() . '.' . $fileExt;
$destination = $uploadDir . $newFileName;

// Movemos el archivo
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    header('Location: update-image.php?id=' . $productoId . '&error=subida_fallida');
    exit;
}

// --- Eliminar imagen anterior (si existe y no es default) ---
$oldImage = $producto->getImagen();
if ($oldImage && $oldImage !== 'default.png') {
    $oldPath = $uploadDir . $oldImage;
    if (file_exists($oldPath)) {
        unlink($oldPath);//Elimina el archivo
    }
}

// Actualizamos la imagen en el producto
$producto->setImagen($newFileName);

try {
    $productoService->update($producto);
    header('Location: index.php?id=' . $productoId . '&success=1');
    exit;
} catch (Exception $e) {
    // Si ocurre un error en la base de datos, eliminamos el archivo subido
    if (file_exists($destination)) {
        unlink($destination);
    }
    header('Location: update-image.php?id=' . $productoId . '&error=error_db');
    exit;
}
?>