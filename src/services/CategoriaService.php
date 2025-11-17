<?php
namespace services;

use config\Config;
use models\Categoria;
use PDO;
use PDOException;

class CategoriaService{
    private PDO $db;//Guarda el objeto PDO Config

    public function __construct(){
        //Obtiene la instancia de Config y usamos su PDO
        $this->db = Config::getInstance()->getDb();
    }
    //Recuperar todas las categorias
    public function findAll(){
        $consultaSql = "SELECT * FROM categorias ORDER BY id";
        $stmt = $this->db->prepare($consultaSql);
        //Captura de errores SQL
        try{
            $stmt->execute();
        }catch(PDOException $e){
            error_log("Error al consultar categorias: " . $e->getMessage());
        }
        //Array vacio para almacenar
        $listacategorias = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $categoria = new Categoria(
            $row['id'],
            $row['nombre'],
            $row['created_at'],
            $row['updated_at'],
            $row['is_deleted']
        );
        $listacategorias[] = $categoria; 
        }
        return $listacategorias;
    }
    //Buscar por ID de categoria
    public function findById(int $id){
    $consultaSql = "SELECT * FROM categorias WHERE id = :id";
    $stmt = $this->db->prepare($consultaSql);
    //Si falla devuelve false
    try {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch(PDOException $e){
        error_log("Error al consultar categoria por ID: " . $e->getMessage());
        return false;
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$row){
        return false;
    }

    return new Categoria(
        $row['id'],
        $row['nombre'],
        $row['created_at'],
        $row['updated_at'],
        $row['is_deleted']
    );
    }

    //Buscar por nombre de categoria
    public function findByName($nombre){
        $consultaSql = "SELECT * FROM categorias WHERE nombre = :nombre";
        //Preparmos consulta y ejecutamos
        $stmt = $this->db->prepare($consultaSql);
        try{
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->execute();  
        }catch(PDOException $e){
            error_log("Error al consultar categoria por nombre: " . $e->getMessage());
            return false;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row){
            return false;
        }
        return new Categoria(
            $row['id'],
            $row['nombre'],
            $row['created_at'],
            $row['updated_at'],
            $row['is_deleted']
        );
    }
}

?>