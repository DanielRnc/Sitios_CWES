<?php
    class Conexion{
        private $host;
        private $user;
        private $password;
        private $dbname;

        public function _construct(){
            $this->host='localhost';
            $this->user='root';
            $this->password='';
            $this->dbname='coches';
        }

        public function connect(){
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";
            // 1. Establecer la conexión PDO
            $pdo = new PDO($dsn, $this->user, $this->password);
            // Configuración para lanzar excepciones
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
    }

    
?>