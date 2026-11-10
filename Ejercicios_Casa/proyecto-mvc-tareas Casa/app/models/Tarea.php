<?php
    class Tarea{
        private $conn;
        private $table_name = "tareas";

        public function __construct($db){
            $this->conn = $db;
        }

        public function obtenerTodas(){
           $query = "SELECT t.id, t.titulo, t.descripcion, t.fase, t.estado, 
                     t.fecha_creacion, u.nombre as nombre_usuario
                    FROM " . $this->table_name . " t
                    LEFT JOIN usuarios u ON t.usuario_id = u.id
                    ORDER BY t.fecha_creacion DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>