<?php

use services\SessionService;
use config\Config;
use models\Producto;
use services\CategoriaService;
use services\ProductoService;

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/services/ProductoService.php';
require_once __DIR__ . '/services/CategoriaService.php';
require_once __DIR__ . '/models/Producto.php';
require_once __DIR__ . '/models/Categoria.php';
require_once __DIR__ . '/config/Config.php';

//Iniciamos el servicio de sesion Instanciada
$session = SessionService::getInstance();
//Instanciamo clases utilizadas
$productoService =  new ProductoService();
$categoriaService = new CategoriaService();
//Iniciamos config Instanciada
$config = Config::getInstance();

//Control de acceso segun roles
if(!$session->isAdmin()){
    header('Location: index.php');
    exit; //Buena practica despues de header
}
//Mandamos cabecera
require_once "header.php";
//Inicializamos variables
$errors = [];
$success = false;

//Procesamiento de el formulario
// trim() elimina espacios al inicio y al final, floatval/intval convierte a tipo numérico
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $marca = trim(filter_input(INPUT_POST, 'marca') ?? '');
    $modelo = trim(filter_input(INPUT_POST, 'modelo') ?? '');
    $descripcion = trim(filter_input(INPUT_POST, 'descripcion') ?? '');    
    $precio = floatval(str_replace(',', '.', $_POST['precio'] ?? 0));
    $stock = intval(filter_input(INPUT_POST, 'stock') ?? 0); 
    $color = trim(filter_input(INPUT_POST, 'color') ?? '');
    $talla = trim(filter_input(INPUT_POST, 'talla') ?? null);
    $categoriaId = intval(filter_input(INPUT_POST, 'categoria') ?? 0);    
    //busacamos numero de categoria
    $categoria = $categoriaService->findAll();
    
    //Validaciones simples de datos
    if($marca === '') $errors['marca'] = 'La marca es obligatoria';
    if($modelo === '') $errors['modelo'] = 'El modelo es obligatorio';
    if($descripcion === '') $errors['descripcion'] = 'La descripcion es obligatoria';
    if($precio <= 0) $errors['precio'] = 'El precio debe ser mayor que 0';
    if($stock < 0) $errors['stock'] = 'El stock no puede ser negativo';
    if($color === '') $errors['color'] = 'El color es obligatorio';
    if($talla === '') $errors['talla'] = 'La talla es obligatoria'; 
    if(empty($categoriaId)) $errors['categoria'] = 'Selecciona una categoria';

    // Manejar imagen subida
$imagenNombre = 'default.png'; // Imagen por defecto en caso de no subir ninguna

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreTmp = $_FILES['imagen']['tmp_name'];
    $nombreArchivo = basename($_FILES['imagen']['name']);
    
    // Generar un nombre único para evitar sobrescribir
    $ext = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
    $imagenNombre = uniqid('prod_') . '.' . $ext;
    
    $rutaDestino = __DIR__ . '/uploads/' . $imagenNombre;


    // Mover archivo subido
    if (!move_uploaded_file($nombreTmp, $rutaDestino)) {
        $errors['imagen'] = 'Error al subir la imagen.';
    }
}

    //Buscamos categoria por id
     $categoria = $categoriaService->findById($categoriaId);
    if (!$categoria) {
        $errors['categoria'] = 'Categoría no válida';
    }
    //Si no hay errores marca true/exito
    if(count($errors) === 0){

         //Creamos el producto asignamos valores
        $producto = new Producto();
        $producto->setMarca($marca);
        $producto->setModelo($modelo);
        $producto->setDescripcion($descripcion);
        $producto->setPrecio($precio);
        $producto->setStock($stock);
        $producto->setColor($color);
        $producto->setTalla($talla);
        $producto->setCategoriaId($categoriaId);
        $producto->setCategoriaNombre($categoria->getNombre());
        $producto->setImagen($imagenNombre);
        $producto->setIsDeleted(false);
        try{
            $productoService->save($producto);
            $success =  true;
          echo "<script type='text/javascript'>
               alert('Producto creado correctamente');
               window.location.href = 'index.php';
                </script>";

            } catch(Exception $e){
            $error = 'Error en el sistema. Por favor intente mas tarde';
        }
    }
}
?>
<!-- Aquí comienza el contenido dentro de un contenedor Bootstrap para centrar y dar margen -->
<div class="container mt-4">
<h1 class="mb-3">Crear producto</h1>
<!-- Mensaje de éxito con alerta Bootstrap -->
<?php if($success): ?>
    <div class="alert alert-success">Producto creado correctamente.</div>
<?php endif; ?>
 <!-- Formulario con clase para que se vea bien -->
<form action="create.php" method="post" enctype="multipart/form-data" class="mx-auto p-3 bg-light rounded shadow-sm" style="max-width: 700px;">
    <div class="mb-3">
        <label for="marca" class="form-label">Marca</label>
        <input type="text" name="marca" class="form-control" value="<?= htmlspecialchars($_POST['marca'] ?? '')?>">
        <span class="text-danger"><?= $errors['marca'] ?? ''?></span>
    </div>
    <div class="mb-3">
        <label for="modelo" class="form-label">Modelo</label>
        <input type="text" name="modelo" class="form-control" value="<?= htmlspecialchars($_POST['modelo'] ?? '')?>">
        <span class="text-danger"><?= $errors['modelo'] ?? ''?></span>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripcion</label>
        <input type="text" name="descripcion" class="form-control" value="<?= htmlspecialchars($_POST['descripcion'] ?? '')?>">
        <span class="text-danger"><?= $errors['descripcion'] ?? ''?></span>
    </div>
     <div class="mb-3">
        <label for="precio" class="form-label">Precio</label>
        <input type="text" name="precio" class="form-control" value="<?= htmlspecialchars($_POST['precio'] ?? '')?>">
        <span class="text-danger"><?= $errors['precio'] ?? ''?></span>
    </div>
     <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" value="<?= htmlspecialchars($_POST['stock'] ?? '')?>">
        <span class="text-danger"><?= $errors['stock'] ?? ''?></span>
    </div>
    <div class="mb-3">
    <label for="categoria" class="form-label">Categoría</label>
    <select name="categoria" class="form-control">
        <option value="">Selecciona una categoría</option>
        <?php
        $categorias = $categoriaService->findAll();
        foreach($categorias as $cat): ?>
            <option value="<?= $cat->getId() ?>" 
                <?= (isset($_POST['categoria']) && $_POST['categoria'] == $cat->getId()) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat->getNombre()) ?>
            </option>
        <?php endforeach; ?>
      </select>    
      <span class="text-danger"><?= $errors['categoria'] ?? '' ?></span>
     </div>
     <div class="mb-3">
        <label for="color" class="form-label">Color</label>
        <input type="text" name="color" class="form-control" value="<?= htmlspecialchars($_POST['color'] ?? '')?>">
        <span class="text-danger"><?= $errors['color'] ?? ''?></span>
    </div>
     <div class="mb-3">
        <label for="talla" class="form-label">Talla</label>
        <input type="text" name="talla" class="form-control" value="<?= htmlspecialchars($_POST['talla'] ?? '')?>">
        <span class="text-danger"><?= $errors['talla'] ?? ''?></span>
    </div>
    <div class="mb-3">
        <label for="imagen" class="form-label">Imagen del producto</label>
        <input type="file" name="imagen" class="form-control" accept="image/*">
    </div>
    <div class="mb-3">
        <!-- Botón con clase Bootstrap -->
        <button type="submit" class="btn btn-primary">Crear Producto</button>
    </div>
</form>
</div>
<?php
require_once "footer.php";
?>