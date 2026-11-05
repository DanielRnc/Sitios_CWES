<?php
/**
 * MODELO: Tarea
 * 
 * Esta clase representa el modelo de Tarea en la aplicación.
 * Gestiona las operaciones CRUD (Create, Read, Update, Delete) sobre las tareas.
 */

class Tarea {
    // Propiedades de la clase
    private $conn;
    private $table_name = "tareas";
    
    public $id;
    public $titulo;
    public $descripcion;
    public $fase;
    public $estado;
    public $usuario_id;
    public $fecha_creacion;
    
    /**
     * Constructor
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Obtener todas las tareas (para la vista pública)
     * @return PDOStatement Resultado de la consulta
     */
    public function obtenerTodas() {
        $query = "SELECT t.id, t.titulo, t.descripcion, t.fase, t.estado, 
                         t.fecha_creacion, u.nombre as nombre_usuario
                  FROM " . $this->table_name . " t
                  LEFT JOIN usuarios u ON t.usuario_id = u.id
                  ORDER BY t.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Obtener tareas de un usuario específico
     * @return PDOStatement Resultado de la consulta
     */
    public function obtenerPorUsuario() {
        $query = "SELECT id, titulo, descripcion, fase, estado, fecha_creacion
                  FROM " . $this->table_name . "
                  WHERE usuario_id = :usuario_id
                  ORDER BY fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->execute();
        
        return $stmt;
    }
    
    /**
     * Obtener una tarea por ID
     * @return bool true si se encuentra, false en caso contrario
     */
    public function obtenerPorId() {
        $query = "SELECT id, titulo, descripcion, fase, estado, usuario_id, fecha_creacion
                  FROM " . $this->table_name . "
                  WHERE id = :id
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->titulo = $row['titulo'];
            $this->descripcion = $row['descripcion'];
            $this->fase = $row['fase'];
            $this->estado = $row['estado'];
            $this->usuario_id = $row['usuario_id'];
            $this->fecha_creacion = $row['fecha_creacion'];
            return true;
        }
        return false;
    }
    
    /**
     * Crear una nueva tarea
     * @return bool true si se crea correctamente, false en caso contrario
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                  (titulo, descripcion, fase, estado, usuario_id)
                  VALUES (:titulo, :descripcion, :fase, :estado, :usuario_id)";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiamos los datos
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->fase = htmlspecialchars(strip_tags($this->fase));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        
        // Vinculamos los parámetros
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":fase", $this->fase);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Actualizar una tarea existente
     * @return bool true si se actualiza correctamente, false en caso contrario
     */
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . "
                  SET titulo = :titulo,
                      descripcion = :descripcion,
                      fase = :fase,
                      estado = :estado
                  WHERE id = :id AND usuario_id = :usuario_id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiamos los datos
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->fase = htmlspecialchars(strip_tags($this->fase));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        
        // Vinculamos los parámetros
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":fase", $this->fase);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Eliminar una tarea
     * @return bool true si se elimina correctamente, false en caso contrario
     */
    public function eliminar() {
        $query = "DELETE FROM " . $this->table_name . "
                  WHERE id = :id AND usuario_id = :usuario_id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
