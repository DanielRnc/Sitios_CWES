<?php
/**
 * Modelo Colmena
 * Representa el recurso "colmena" en la API REST
 * Gestiona todas las operaciones CRUD sobre colmenas
 */

class Colmena {
    // Conexión a la base de datos
    private $conn;
    
    // Nombre de la tabla
    private $table_name = "colmenas";
    
    // Propiedades del objeto Colmena (corresponden a columnas de la tabla)
    public $id;
    public $zona;
    public $usuario_id;
    public $fecha_creacion;
    
    // Propiedades adicionales para respuestas
    public $usuario_nombre;

    /**
     * Constructor
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtiene todas las colmenas con información del usuario
     * Endpoint: GET /colmenas
     * @return PDOStatement|false
     */
    public function obtenerTodas() {
        $query = "SELECT 
                    c.id,
                    c.zona,
                    c.usuario_id,
                    c.fecha_creacion,
                    u.username,
                    u.nombre_completo as usuario_nombre
                  FROM " . $this->table_name . " c
                  LEFT JOIN usuarios u ON c.usuario_id = u.id
                  ORDER BY c.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    /**
     * Obtiene una colmena por su ID
     * Endpoint: GET /colmenas/{id}
     * @param int $id ID de la colmena
     * @return bool True si se encontró, false si no
     */
    public function obtenerPorId($id) {
        $query = "SELECT 
                    c.id,
                    c.zona,
                    c.usuario_id,
                    c.fecha_creacion,
                    u.username,
                    u.nombre_completo as usuario_nombre
                  FROM " . $this->table_name . " c
                  LEFT JOIN usuarios u ON c.usuario_id = u.id
                  WHERE c.id = :id
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            
            // Asignar valores a las propiedades del objeto
            $this->id = $row['id'];
            $this->zona = $row['zona'];
            $this->usuario_id = $row['usuario_id'];
            $this->fecha_creacion = $row['fecha_creacion'];
            $this->usuario_nombre = $row['usuario_nombre'];
            
            return true;
        }
        
        return false;
    }

    /**
     * Crea una nueva colmena
     * Endpoint: POST /colmenas
     * @return bool True si se creó correctamente, false si no
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET zona = :zona,
                      usuario_id = :usuario_id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar y validar datos
        $this->zona = htmlspecialchars(strip_tags($this->zona));
        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
        
        // Bind de parámetros
        $stmt->bindParam(':zona', $this->zona);
        $stmt->bindParam(':usuario_id', $this->usuario_id);
        
        if ($stmt->execute()) {
            // Obtener el ID de la colmena recién creada
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }

    /**
     * Actualiza una colmena existente
     * Endpoint: PUT /colmenas/{id}
     * @return bool True si se actualizó correctamente, false si no
     */
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . "
                  SET zona = :zona,
                      usuario_id = :usuario_id
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar y validar datos
        $this->zona = htmlspecialchars(strip_tags($this->zona));
        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind de parámetros
        $stmt->bindParam(':zona', $this->zona);
        $stmt->bindParam(':usuario_id', $this->usuario_id);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    /**
     * Elimina una colmena
     * Endpoint: DELETE /colmenas/{id}
     * @return bool True si se eliminó correctamente, false si no
     */
    public function eliminar() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    /**
     * Valida que los datos de la colmena sean correctos
     * @return array Array con 'valido' (bool) y 'errores' (array)
     */
    public function validar() {
        $errores = [];
        
        if (empty($this->zona)) {
            $errores[] = "El campo 'zona' es obligatorio";
        }
        
        if (strlen($this->zona) > 100) {
            $errores[] = "El campo 'zona' no puede tener más de 100 caracteres";
        }
        
        if (empty($this->usuario_id)) {
            $errores[] = "El campo 'usuario_id' es obligatorio";
        }
        
        if (!is_numeric($this->usuario_id) || $this->usuario_id <= 0) {
            $errores[] = "El campo 'usuario_id' debe ser un número positivo";
        }
        
        return [
            'valido' => empty($errores),
            'errores' => $errores
        ];
    }
}
