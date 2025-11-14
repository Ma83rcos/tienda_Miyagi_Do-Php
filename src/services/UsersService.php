<?php

namespace services;

use config\Config;
use models\Usuario;
use PDO;

class UsersService {

    private PDO $db;

    // Guarda el objeto PDO de Config
    public function __construct() {
        // Obtenemos la instancia de Config y usamos su PDO
        $this->db = Config::getInstance()->getDb();
    }

    // Busca un usuario por su nombre de usuario
    public function findUsersByUsername(string $username): ?Usuario {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE username = :username AND is_deleted = false");
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }
        //Traer los roles del usuario
        $roleStmt = $this->db->prepare("SELECT roles FROM user_roles WHERE user_id = :user_id");
        $roleStmt->execute(['user_id' => $row['id']]);
        $roles = $roleStmt->fetchAll(PDO::FETCH_COLUMN);

        // Retorna un objeto Usuario con los datos de la base de datos
        return new Usuario(
            id: $row['id'],
            nombre: $row['nombre'],
            apellidos: $row['apellidos'],
            email: $row['email'],
            username: $row['username'],
            password: $row['password'], // ya está hasheada
            createdAt: $row['created_at'],
            updatedAt: $row['updated_at'],
            isDeleted: $row['is_deleted'],
            roles: $roles,//Array roles
            isHashed:true
        ); 
    }

    // Autenticación de usuario validando contraseña
    public function authenticate(string $username, string $password): ?Usuario {
        $user = $this->findUsersByUsername($username);

        // Si no existe o no coincide la contraseña
        if (!$user || !password_verify($password, $user->getPassword())) {
            return null;
        }

        return $user;
    }
}
