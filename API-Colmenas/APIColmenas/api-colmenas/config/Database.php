<?php
/**
 * Clase Database
 * Gestiona la conexión a la base de datos usando PDO
 * Patrón Singleton para una única instancia de conexión
 */

class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $charset = DB_CHARSET;
    private $conn = null;

    /**
     * Obtiene la conexión a la base de datos
     * @return PDO|null Objeto PDO de conexión
     */
    public function getConnection() {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
                
                $this->conn = new PDO($dsn, $this->username, $this->password);
                
                // Configurar PDO para lanzar excepciones en caso de error
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Configurar PDO para devolver arrays asociativos
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
            } catch(PDOException $e) {
                // En producción, no mostrar detalles del error
                error_log("Error de conexión: " . $e->getMessage());
                return null;
            }
        }
        return $this->conn;
    }
}
