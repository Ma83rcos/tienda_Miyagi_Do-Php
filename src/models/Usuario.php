<?php
namespace models;

class Usuario{
    private $id;
    private $nombre;
    private $apellidos;
    private $email;
    private $username;
    private $password;
    private $createdAt;
    private $updatedAt;
    private $isDeleted;
    private $roles;

    public function __construct($id=null, $nombre=null, $apellidos=null, $email=null, $username=null,
                                $password=null, $createdAt=null, $updatedAt=null, $isDeleted=false, $isHashed=false, $roles=[]){
        $this->id = $id; // ID numérico
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->username = $username;
        //Solo hashea si no es hash ya existente
        $this->password = $password ?  ($isHashed ? $password : password_hash($password, PASSWORD_BCRYPT)) : null; // Hash de contraseña
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
        $this->updatedAt = $updatedAt ?? date('Y-m-d H:i:s');
        $this->isDeleted = $isDeleted;
        $this->roles = $roles;
     }

     // Getters
     public function getId(){ return $this->id; }
     public function getNombre(){ return $this->nombre; }
     public function getApellidos(){ return $this->apellidos; }
     public function getEmail(){ return $this->email; }
     public function getUsername(){ return $this->username; }
     public function getPassword(){ return $this->password; }
     public function getCreatedAt(){ return $this->createdAt; }
     public function getUpdatedAt(){ return $this->updatedAt; }
     public function isDeleted(){ return $this->isDeleted; }
     public function getRoles(){return $this->roles;}

     // Setters
     public function setNombre($nombre){ $this->nombre = $nombre; }
     public function setApellidos($apellidos){ $this->apellidos = $apellidos; }
     public function setEmail($email){ $this->email = $email; }
     public function setUsername($username){ $this->username = $username; }
     public function setPassword($password, $isHashed = false){  $this->password = $isHashed ? $password : password_hash($password, PASSWORD_BCRYPT); }
     public function setUpdatedAt($updatedAt){ $this->updatedAt = $updatedAt; }
     public function setIsDeleted($isDeleted){ $this->isDeleted = $isDeleted; }
     public function setRoles($roles){ $this->roles = $roles;}
}
?>
