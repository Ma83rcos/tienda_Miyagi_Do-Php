<?php
namespace models;

class Producto{
    private $id;
    private $categoriaId;
    private $descripcion;
    private $precio;
    private $stock;
    private $marca;
    private $modelo;
    private $color;
    private $talla;
    private $imagen;
    private $createdAt;
    private $updatedAt;
    private $categoriaNombre;
    private $isDeleted;

    public function __construct($id=null, $categoriaId=null, $descripcion=null, $precio=0, $stock=0, $marca=null, $modelo=null,
                                $color=null, $talla=null, $imagen=null, $createdAt=null, $updatedAt=null, $categoriaNombre=null, $isDeleted=false){
        $this->id = $id; // ID numérico
        $this->categoriaId = $categoriaId;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->color = $color;
        $this->talla = $talla;
        $this->imagen = $imagen;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');//Si viene valor se sasigan si no asigna fecha actual
        $this->updatedAt = $updatedAt;
        $this->categoriaNombre = $categoriaNombre;
        $this->isDeleted = $isDeleted;
    }

    // Getters
    public function getId(){ return $this->id; }
    public function getCategoriaId(){ return $this->categoriaId; }
    public function getDescripcion(){ return $this->descripcion; }
    public function getPrecio(){ return $this->precio; }
    public function getStock(){ return $this->stock; }
    public function getMarca(){ return $this->marca; }
    public function getModelo(){ return $this->modelo; }
    public function getColor(){ return $this->color; }
    public function getTalla(){ return $this->talla; }
    public function getImagen(){ return $this->imagen; }
    public function getCreatedAt(){ return $this->createdAt; }
    public function getUpdatedAt(){ return $this->updatedAt; }
    public function getCategoriaNombre(){return $this->categoriaNombre;}
    public function getIsDeleted(){ return $this->isDeleted; }

    // Setters
    public function setDescripcion($descripcion){ $this->descripcion = $descripcion; }
    public function setPrecio($precio){ $this->precio = $precio; }
    public function setStock($stock){ $this->stock = $stock; }
    public function setMarca($marca){ $this->marca = $marca; }
    public function setModelo($modelo){ $this->modelo = $modelo; }
    public function setColor($color){ $this->color = $color; }
    public function setTalla($talla){ $this->talla = $talla; }
    public function setImagen($imagen){ $this->imagen = $imagen; }
    public function setUpdatedAt($updatedAt){ $this->updatedAt = $updatedAt; }
    public function setIsDeleted($isDeleted){ $this->isDeleted = $isDeleted; }
    public function setCategoriaNombre($categoriaNombre){$this->categoriaNombre = $categoriaNombre;}
    public function setCategoriaId($categoriaId){$this->categoriaId = $categoriaId;}
}
?>
