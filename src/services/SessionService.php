<?php
namespace services;
class SessionService{
    //Una unica instancia de la clase patron singleton la almacena
    private static ?SessionService $instance = null;

    //La sesion caducara en una hora en segundos
    private int $expireAfterSeconds = 3600;
    //Contructor privado para obtener la unica instancia disponible
    private function __construct(){
        //Inicia sesion si no esta activa
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
    }
    //Metodo para obtener la unica instancia disponible
    public static function getInstance(): SessionService{
        //Si no exixte la intacia se crea
        if(self::$instance === null){
           self::$instance = new SessionService();
        }
        //retorna la instancia exixtente
          return self::$instance;
        }
    //Metodo para guardar datos de usuario y marca si esta logueado
    public function login(array $userData){
       $_SESSION['loggedIn'] = true;
       $_SESSION['user_id'] = $userData['id'];
       $_SESSION['username'] = $userData['username'];
       $_SESSION['role'] = $userData['role']??'user';//Indica el rol enviado o sin esta vacio no se indica
       $_SESSION['last_activity'] = time();
    }    
    //Cerrar sesion
    public function logout(){
        $_SESSION = [];//Borra todas las varibales de sesion
        if(session_status() !== PHP_SESSION_NONE){
            session_destroy();//Termina sesion
        }
        unset($_SESSION); // ← Limpia completamente la variable
    }
    //Verifica si esta Logueado y controla expiracion de sesion
    public function isLoggedIn(): bool{
        if(!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true){
            return false;
        }
        //Comprobar si a pasdo el timpo permitido 1 hora
        if(isset($_SESSION['last_activity']) &&  (time() - $_SESSION['last_activity'] > $this->expireAfterSeconds)){
        $this->logout();
        return false;
    }
     $_SESSION['last_activity'] = time();
     return true;   
    }
    //Devuelve los datos del usuario logueado
    public function getUser(): ?array{
        if(!$this->isLoggedIn()){
            return null;
        }
        return [
                 'id' => $_SESSION['user_id'],
                 'username' => $_SESSION['username'],
                 'role' => $_SESSION['role']
        ];
    }
    //Comprobar rol del usuario
    public function isAdmin(): bool {
     if (!$this->isLoggedIn()) return false;
    $role = $_SESSION['role'] ?? null;
    // Si el rol es un array, buscamos 'admin' dentro
    if (is_array($role)) {
        $role = array_map('strtolower', $role);
        return in_array('admin', $role,true);
    }
    // Si es un string, comparamos directamente
    return strtolower((string)$role) === 'admin';
    }
}
?>