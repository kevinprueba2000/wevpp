<?php
class Database {
    /**
     * Parámetros de conexión. Permiten sobrescribir valores mediante variables
     * de entorno para facilitar la configuración en distintos entornos de
     * ejecución (desarrollo, pruebas, producción).
     */
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $pdo;

    public function __construct() {
        $this->host = getenv('DB_HOST') ?: (defined('DB_HOST') ? DB_HOST : 'localhost');
        $this->dbname = getenv('DB_NAME') ?: (defined('DB_NAME') ? DB_NAME : 'alquimia_technologic');
        $this->username = getenv('DB_USER') ?: (defined('DB_USER') ? DB_USER : 'root');
        $this->password = getenv('DB_PASS') ?: (defined('DB_PASS') ? DB_PASS : '');
    }
    
    public function connect() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            return $this->pdo;
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        if (!$this->pdo) {
            $this->connect();
        }
        return $this->pdo;
    }
}
?> 