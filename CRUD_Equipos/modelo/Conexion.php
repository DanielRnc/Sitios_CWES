<?php
class Conexion
{
    public $host;
    public $user;
    public $password;
    public $dbname;
    public $tabla;

    public function __construct() {
        $this->host ='localhost';
        $this->user ='root';
        $this->password ='';
        $this->dbname ='equipos';
        $this->tabla ='equipos';
    }

    public function connect(){
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";

        try {
            //Establecer la conexión PDO
            $pdo = new PDO($dsn, $this->user, $this->password);
            // Configuración para lanzar excepciones en caso de error
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Devolvemos la conexión
            return $pdo;
        } catch (PDOException $e) {
            die("Error en la base de datos durante la conexión: " . $e->getMessage());
        }
    }
}