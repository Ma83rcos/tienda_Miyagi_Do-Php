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

$session = SessionService::getInstance();
$productoService =  new ProductoService();
$categoriaService = new CategoriaService();
$config = Config::getInstance();

if (!$session->isAdmin()) {
    header('Location: index.php');
    exit;
}

require_once "header.php";

$errors = [];
$success = false;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: productos.php?error=invalid_id');
    exit;
}

$productoId = (int)$_GET['id'];
$producto = $productoService->findById($productoId);

if(!$producto){
    header('Location: productos.php?error=not_found');
    exit;
}

$categorias = $categoriaService->findAll();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $marca = trim(filter_input(INPUT_POST, 'marca') ?? '');
    $modelo = trim(filter_input(INPUT_POST, 'modelo') ?? '');
    $descripcion = trim(filter_input(INPUT_POST, 'descripcion') ?? '');    
    $precio = floatval(str_replace(',', '.', filter_input(INPUT_POST, 'precio') ?? 0));
    $stock = intval(filter_input(INPUT_POST, 'stock') ?? 0); 
    $color = trim(filter_input(INPUT_POST, 'color') ?? '');
    $talla = trim(filter_input(INPUT_POST, 'talla') ?? '');
    $categoriaId = intval(filter_input(INPUT_POST, 'categoria_id') ?? 0); 

    // Validaciones
    if($marca === '') $errors['marca'] = 'La marca es obligatoria';
    if($modelo === '') $errors['modelo'] = 'El modelo es obligatorio';
    if($descripcion === '') $errors['descripcion'] = 'La descripcion es obligatoria';
    if($precio <= 0) $errors['precio'] = 'El precio debe ser mayor que 0';
    if($stock < 0) $errors['stock'] = 'El stock no puede ser negativo';
    if($color === '') $errors['color'] = 'El color es obligatorio';
    if($talla === '') $errors['talla'] = 'La talla es obligatoria'; 
    if(empty($categoriaId)) $errors['categoria_id'] = 'Selecciona una categoria';

    if(count($errors) === 0){
        try{
            // Asignamos valores al objeto
            $producto->setMarca($marca);
            $producto->setModelo($modelo);
            $producto->setDescripcion($descripcion);
            $producto->setPrecio($precio);
            $producto->setStock($stock);
            $producto->setColor($color);
            $producto->setTalla($talla);
            $producto->setCategoriaId($categoriaId);

            // Actualizamos producto y capturamos filas afectadas
            $resultado = $productoService->update($producto);

            if($resultado){
                $success = true;
                echo "<script type='text/javascript'>
                     alert('Producto actualizado correctamente');
                     window.location.href = 'index.php';
                    </script>";
            } else {
                echo "<div class='alert alert-warning'>No se modificó ningún dato (valores iguales a los existentes o ID incorrecto).</div>";
            }

        } catch(PDOException $e){
            $errors['general'] = 'Error al actualizar: ' . $e->getMessage();
            echo "<pre>Error PDO: " . $e->getMessage() . "</pre>";
        }
    }
}
?>

<div class="container mt-4">
    <h2>Editar producto</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Producto actualizado correctamente.</div>
    <?php endif; ?>

    <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger"><?= $errors['general'] ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <!-- Marca -->
        <div class="mb-3">
            <label class="form-label">Marca</label>
            <input type="text" name="marca" value="<?= htmlspecialchars($producto->getMarca()) ?>" class="form-control">
            <?php if (!empty($errors['marca'])): ?>
                <div class="text-danger"><?= $errors['marca'] ?></div>
            <?php endif; ?>
        </div>

          <!-- Modelo -->
        <div class="mb-3">
            <label class="form-label">Modelo</label>
            <input type="text" name="modelo" value="<?= htmlspecialchars($producto->getModelo()) ?>" class="form-control">
            <?php if (!empty($errors['modelo'])): ?>
                <div class="text-danger"><?= $errors['modelo'] ?></div>
            <?php endif; ?>
        </div>

        <!-- Descripción -->
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control"><?= htmlspecialchars($producto->getDescripcion()) ?></textarea>
        </div>

        <!-- Precio -->
        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($producto->getPrecio()) ?>" class="form-control">
            <?php if (!empty($errors['precio'])): ?>
                <div class="text-danger"><?= $errors['precio'] ?></div>
            <?php endif; ?>
        </div>

        <!-- Stock -->
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" value="<?= htmlspecialchars($producto->getStock()) ?>" class="form-control">
            <?php if (!empty($errors['stock'])): ?>
                <div class="text-danger"><?= $errors['stock'] ?></div>
            <?php endif; ?>
        </div>

        <!-- Color -->
        <div class="mb-3">
            <label class="form-label">Color</label>
            <input type="text" name="color" value="<?= htmlspecialchars($producto->getColor()) ?>" class="form-control">
            <?php if (!empty($errors['color'])): ?>
                <div class="text-danger"><?= $errors['color'] ?></div>
            <?php endif; ?>
        </div>

        <!-- Talla -->
        <div class="mb-3">
            <label class="form-label">Talla</label>
            <input type="text" name="talla" value="<?= htmlspecialchars($producto->getTalla()) ?>" class="form-control">
            <?php if (!empty($errors['talla'])): ?>
                <div class="text-danger"><?= $errors['talla'] ?></div>
            <?php endif; ?>
        </div>

        <!-- Categoría -->
        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" class="form-select">
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat->getId() ?>" <?= $cat->getId() == $producto->getCategoriaId() ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat->getNombre()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="index.php" class="btn btn-secondary">Volver</a>
    </form>
</div>

<?php require_once "footer.php"; ?>