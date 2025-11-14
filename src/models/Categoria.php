<?php
namespace models;

class Categoria{
    private $id;
    private $nombre;
    private $createdAt;
    private $updatedAt;
    private $isDeleted;

    public function __construct($id=null, $nombre=null, $createdAt=null, $updatedAt=null, $isDeleted=false){
        $this->id = $id; // Ahora es un número, no se genera UUID
        $this->nombre = $nombre;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
        $this->updatedAt = $updatedAt;
        $this->isDeleted = $isDeleted;
    }

    // Getters
    public function getId(){
        return $this->id;
    } 

    public function getNombre(){
        return $this->nombre;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function getUpdatedAt(){
        return $this->updatedAt;
    }

    public function getIsDeleted(){
        return $this->isDeleted;
    }

    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setUpdatedAt($updatedAt) { $this->updatedAt = $updatedAt; }
    public function setIsDeleted($isDeleted) { $this->isDeleted = $isDeleted; }
}
?>
