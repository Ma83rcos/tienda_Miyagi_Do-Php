<?php

namespace config;

use Dotenv\Dotenv;  // Para cargar variables de entorno desde archivo .env
use PDO;            // Para la conexión a la base de datos usando PDO
use Exception;      //Para manejar errores
class Config
{
 // Instancia estática para el patrón Singleton (una única instancia)
    private static $instance;
// Variables para la configuración de conexión a PostgreSQL    
    private $postgresDb;
    private $postgresUser;
    private $postgresPassword;
    private $postgresHost;
    private $postgresPort;
// Objeto PDO para la conexión a la base de datos    
    private $db;
   
// Rutas para subir archivos y ruta raíz del proyecto (ajustar según entorno)
    private $rootPath = '/var/www/html/';
   
 // Constructor privado para evitar que se instancie desde fuera (Singleton)
    private function __construct(){
        //Excepcion por si hay fallo en lectura .env
        try{
          // Crear una instancia de Dotenv para cargar variables desde el archivo .env ubicado en rootPath
        $dotenv = Dotenv::createImmutable($this->rootPath);
        $dotenv->load();
        // Obtener las variables de entorno o asignar valores por defecto si no existen
        $this->postgresDb = getenv('DATABASE_NAME') ?? 'Bd_MiyagiDo';
        $this->postgresUser = getenv('DATABASE_USER') ?? 'root';
        $this->postgresPassword = getenv('DATABASE_PASSWORD') ?? '123456';
        $this->postgresHost = getenv('DATABASE_HOST') ?? 'postgres-db';
        $this->postgresPort = intval(getenv('DATABASE_PORT') ?? '5432');
        if ($this->postgresPort <= 0) {
            $this->postgresPort = 5432; // Valor por defecto seguro
        }
         // Crear la conexión PDO con la base de datos PostgreSQL usando las variables anteriores
        $this->db = new PDO("pgsql:host={$this->postgresHost};port={$this->postgresPort};dbname={$this->postgresDb}",
                                 $this->postgresUser,
                                 $this->postgresPassword);
        //Permite capturar errores de BD mas clara y controlada
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);                         
    }catch(Exception $e){
        throw new Exception("Error al conectar a la base de datos: " . $e->getMessage());
    }
}
    // Método estático para obtener la instancia única de la clase (Singleton)
    public static function getInstance(): Config
    {
        if (!isset(self::$instance)) {
            self::$instance = new Config();
        }
        return self::$instance;
    }


    // Método mágico para asignar valor a una propiedad privada
    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

     // Getter explícito para PDO
    public function getDb(): PDO {
        return $this->db;
    }

}