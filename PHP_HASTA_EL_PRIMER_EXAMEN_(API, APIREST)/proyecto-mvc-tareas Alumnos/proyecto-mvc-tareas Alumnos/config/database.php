<?php
/**
 * Configuración de la Base de Datos
 * Este archivo contiene los parámetros de conexión a la base de datos
 */

class Database {
    private $host = "localhost";
    private $db_name = "tareas_db";
    private $username = "root";
    private $password = "";
    private $conn;

    /**
     * Obtiene la conexión a la base de datos
     * @return PDO|null Objeto de conexión o null si falla
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
