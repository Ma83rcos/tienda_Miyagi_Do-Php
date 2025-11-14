<?php
// Carga automática de clases
require_once __DIR__ . '/../vendor/autoload.php';

// Trae la conexión a la base de datos con instancia config.php
use config\Config;
use services\ProductoService;
use services\SessionService;

//Obtener sesion
$session = SessionService::getInstance();
//Conexion base datos
$db = Config::getInstance()->getDb();

// Importa clases necesarias

//Para las sesiones y configuracion


// Lee el parámetro de búsqueda si existe
$productoService = new ProductoService();
$buscar = $_GET['buscar'] ?? '';
// Busquedas con filtro o sin filtro (ILIKE: busca en mayúsculas y minúsculas) llamndo al metodo de productosService
$productos = $productoService->findAllWithCategoryName($buscar);
// Incluye el header con la barra de navegación
require_once 'header.php';
?>

<!-- Contenido principal de la página -->
<main class="container mt-5">
    <h1>Bienvenido al Proyecto 🥋</h1>

    <!-- Formulario de búsqueda -->
    <form  action="index.php" class="busqueda" method="get"  >
        <input type="text" name="buscar" placeholder="Buscar por marca o color" value="<?= htmlspecialchars($buscar) ?>"/>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form> 

    <!-- Tabla de productos -->
   <table class="table table-striped table-bordered">  
     <thead class="tabla">
        <tr>
            <th>Imagen</th>
            <th>Marca</th>
            <th>Categoria</th>
            <th>Modelo</th>
            <th>Color</th>
            <th>Descripcion</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
     </thead>
     <tbody>
      <?php if(count($productos) === 0): ?>
         <tr>
            <td colspan="9" class="text-center">No se encontraron productos.</td>
         </tr>  
      <?php else: ?>   
        <?php foreach($productos as $p): ?>
        <tr>
            <td>
             <?php 
               $imagen = $p->getImagen(); // obtiene el nombre de archivo
               $ruta = 'uploads/' . $imagen; // ruta correcta (sin "src/")

                if (!empty($imagen) && file_exists($ruta)) {
                // Si el archivo existe, lo muestra
                echo '<img src="' . htmlspecialchars($ruta) . '" alt="Imagen del producto" style="max-width:100px; max-height:100px;">';
                } else {
                // Si no existe, muestra un placeholder
                echo '<img src="https://via.placeholder.com/100x100?text=Sin+Imagen" alt="Sin imagen" style="max-width:100px; max-height:100px;">';
                }
             ?>
            </td>
            <td><?= htmlspecialchars($p->getMarca()) ?></td>
            <td><?= htmlspecialchars($p->getCategoriaNombre()) ?></td>
            <td><?= htmlspecialchars($p->getModelo()) ?></td>
            <td><?= htmlspecialchars($p->getColor()) ?></td>
            <td><?= htmlspecialchars($p->getDescripcion()) ?></td>
            <td><?= number_format($p->getPrecio(), 2) ?></td>
            <td><?= htmlspecialchars($p->getStock()) ?></td>
            <td>
                <div style="display: flex; justify-content: center; gap: 5px;">
                    <!-- Botón Detalles: siempre visible -->
                    <a href="details.php?id=<?= $p->getId() ?>" class="btn btn-info btn-sm">Detalles</a>
                    <!-- Botones solo visibles para administradores -->
                    <?php if($session->isAdmin()): ?>
                    <div class="botonesAdministrador" style="display: flex; gap: 5px;">     
                      <a href="update.php?id=<?= $p->getId() ?>" class="btn btn-warning btn-sm">Editar</a>
                      <a href="update_image.php?id=<?= $p->getId() ?>" class="btn btn-secondary btn-sm">Imagen</a>
                      <a href="delete.php?id=<?= $p->getId() ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar este producto?');">Eliminar</a>
                    </div>
                    <?php endif; ?>  
                </div>           
            </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
     </tbody>   
 </table> 
  <!-- Enlace para crear un producto solo si el usuario es administrador -->
                <?php if ($session->isAdmin()): ?>
                        <a class="btn btn-sm btn-success" href="create.php">Nuevo Producto</a>
                <?php endif; ?>

</main>

<?php
// Incluye el footer que cierra el body y html
require_once 'footer.php';
?>