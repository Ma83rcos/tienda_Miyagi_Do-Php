<?php
namespace services;

use config\Config;
use models\Producto;
use PDO;

use PDOException;

class ProductoService{
    private PDO $db; // Guarda el objeto PDO Config

    public function __construct(){
        // Obtenemos la instancia de Config y usamos su PDO
        $this->db = Config::getInstance()->getDb();

    }

    // Recupera todos los productos por categoría
      public function findAllWithCategoryName(?string $searchTerm = null): array {
          try {
            // Consulta base: selecciona todos los productos no eliminados y su categoría
            $sql = "SELECT productos.*, categorias.nombre AS categoria_nombre
                    FROM productos
                    LEFT JOIN categorias ON productos.categoria_id = categorias.id
                    WHERE 1=1"; //Como truco para poder concatenar filtros

            // Si se proporciona término de búsqueda, agregamos filtro por marca o color
            if (!empty($searchTerm)) {
                $sql .= " AND (productos.marca ILIKE :searchTerm OR productos.color ILIKE :searchTerm)";
            }

            // Preparamos la consulta
            $stmt = $this->db->prepare($sql);

            // Si hay búsqueda, vinculamos el parámetro con % para ILIKE
            if (!empty($searchTerm)) {
                $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
            }

            // Ejecutamos la consulta
            $stmt->execute();

            $productos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $producto = new Producto(
                $row['id'],          // ID
                $row['categoria_id'], 
                $row['descripcion'],
                $row['precio'],
                $row['stock'],
                $row['marca'],
                $row['modelo'],
                $row['color'],
                $row['talla'],
                $row['imagen'],
                $row['created_at'],
                $row['updated_at'],
                $row['categoria_nombre'],
                isDeleted: $row['is_deleted']
            );
            $productos[] = $producto;
        }
        return $productos;
        }catch(PDOException $e){
            error_log("Error en ProductoService::findAllWithCategoryName" . $e->getMessage());
            return [];
        }
    }

    // Buscar producto específico por su id
    public function findById($id){
        $consultaSql = "SELECT productos.*, categorias.nombre AS categoria_nombre 
                        FROM productos
                        JOIN categorias ON productos.categoria_id = categorias.id
                        WHERE productos.id = :id AND productos.is_deleted = FALSE
                        LIMIT 1";

        $stmt = $this->db->prepare($consultaSql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row){
            return null; // No se encontró el producto
        }

        $producto = new Producto(
            $row['id'],
            $row['categoria_id'], 
            $row['descripcion'],
            $row['precio'],
            $row['stock'],
            $row['marca'],
            $row['modelo'],
            $row['color'],
            $row['talla'],
            $row['imagen'],
            $row['created_at'],
            $row['updated_at'],
            $row['categoria_nombre'],
            $row['is_deleted']
        );

        return $producto;
    }   
    /**
     * Metodo agregar producto
     * Inserta un nuevo producto en la base de datos.
     * Asigna imagen por defecto y establece fechas automáticamente.
     */
    public function save(Producto $producto){
    
    try{
            //Imagen por defecto si no se prorporciona
            $imagen = $producto->getImagen()?: 'default.png';
            //Fecha de creacion y actualizacion
            $now = date('Y-m-d H:i:s');
            //Cosulta Sql
            $consultaSql = "INSERT INTO productos
                            (categoria_id, descripcion, precio, stock, marca, modelo, color, talla, imagen, created_at, updated_at, is_deleted)
                            VALUES
                            (:categoria_id, :descripcion, :precio, :stock, :marca, :modelo, :color, :talla, :imagen, :created_at, :updated_at, :is_deleted)";
            $stmt = $this->db->prepare($consultaSql);
            //Asignar valores
            $stmt->bindValue(':categoria_id', $producto->getCategoriaId(),PDO::PARAM_INT);
            $stmt->bindValue(':descripcion',$producto->getDescripcion(),PDO::PARAM_STR);
            $stmt->bindValue(':precio',$producto->getPrecio(),PDO::PARAM_STR);
            $stmt->bindValue(':stock',$producto->getStock(),PDO::PARAM_INT);
            $stmt->bindValue(':marca',$producto->getMarca(),PDO::PARAM_STR);
            $stmt->bindValue(':modelo',$producto->getModelo(),PDO::PARAM_STR);
            $stmt->bindValue(':color',$producto->getColor(),PDO::PARAM_STR);
            $stmt->bindValue(':talla',$producto->getTalla(),PDO::PARAM_STR);
            $stmt->bindValue(':imagen',$imagen, PDO::PARAM_STR);
            $stmt->bindValue(':created_at',$now, PDO::PARAM_STR);    
            $stmt->bindValue(':updated_at',$now, PDO::PARAM_STR);
            $stmt->bindValue(':is_deleted', false, PDO::PARAM_BOOL);

            //Ejecutar la iserccion de datos
            return $stmt->execute();                         
        }catch(PDOException $e){
            error_log("Error en ProductoService::save " . $e->getMessage());
            return false;
        }
}
     
//Metodo para actualizar o mdificar producto
    public function update(Producto $producto){
        try{
            //Fecha de actualizacion/modificacion actual
            $now = date('Y-m-d H:i:s');
            //Imagen por defecto si no se proporcona
            $imagen = $producto->getImagen()?: 'default.png';
            //Consulta para actualizar prodicto existenete
            $consultaSql = "UPDATE productos
                            SET categoria_id = :categoria_id,
                                descripcion = :descripcion,
                                precio = :precio,
                                stock = :stock,
                                marca = :marca,
                                modelo = :modelo,
                                color = :color,
                                talla = :talla,
                                imagen = :imagen,
                                updated_at = :updated_at
                                WHERE id = :id AND is_deleted = FALSE"; // AND is_deleted = FALSE";
            $stmt = $this->db->prepare($consultaSql);       
            //Asiganr valores  modificar
            $stmt->bindValue(':categoria_id', $producto->getCategoriaId(), PDO::PARAM_INT);
            $stmt->bindValue(':descripcion', $producto->getDescripcion(), PDO::PARAM_STR);
            $stmt->bindValue(':precio', $producto->getPrecio(), PDO::PARAM_STR);
            $stmt->bindValue(':stock', $producto->getStock(), PDO::PARAM_INT);
            $stmt->bindValue(':marca', $producto->getMarca(), PDO::PARAM_STR);
            $stmt->bindValue(':modelo', $producto->getModelo(), PDO::PARAM_STR);
            $stmt->bindValue(':color', $producto->getColor(), PDO::PARAM_STR);
            $stmt->bindValue(':talla', $producto->getTalla(), PDO::PARAM_STR);
            $stmt->bindValue(':imagen', $imagen, PDO::PARAM_STR);
            $stmt->bindValue(':updated_at', $now, PDO::PARAM_STR);
            $stmt->bindValue(':id', $producto->getId(), PDO::PARAM_INT);  
            
            //Ejecutar actualizacion
             return $stmt->execute();  
             
            }catch(PDOException $e){
                error_log('Error en ProductoService::update ' . $e->getMessage());
                return false;
            }
    }
    //Metodo para eliminar producto
    public function deleteById( $id){
        try{
              // Validar ID
        $id = intval($id);
        if ($id <= 0) {
            error_log("ProductoService::deleteById - ID inválido: $id");
            return false;
        }
            //Cosnsulta sql
            $consultaSql = "DELETE FROM productos WHERE id = :id";
            $stmt = $this->db->prepare($consultaSql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            //Ejecutar la eliminacion producto
            return $stmt->execute();
        }catch(PDOException $e){
            error_log('Error en ProductosService::deleteById ' . $e->getMessage());
            return false;
        }
    }
}
?>
