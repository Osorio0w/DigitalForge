<?php
/**
 * Configuración de conexión a la base de datos
 * DigitalForge E-commerce
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'digitalforge_db';
    private $username = 'root';
    private $password = '';
    private $conn;
    private $error;

    // Conexión a la base de datos
    public function connect() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );
            
            //echo "Conexión exitosa a la base de datos"; // Comentar después de probar
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            die("Error de conexión: " . $this->error);
        }
        
        return $this->conn;
    }

    // Obtener error
    public function getError() {
        return $this->error;
    }
}

// Función auxiliar para obtener conexión
function getConnection() {
    $database = new Database();
    return $database->connect();
}

// Comprobar si estamos en desarrollo
function isDevelopment() {
    return ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1');
}
?>