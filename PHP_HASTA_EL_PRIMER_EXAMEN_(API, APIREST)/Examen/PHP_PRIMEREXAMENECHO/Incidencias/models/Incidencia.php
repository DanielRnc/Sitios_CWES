<?php
/**
 * Modelo Incidencia
 * Gestiona las operaciones sobre la tabla incidencias
 */
class Incidencia
{
    // Conexión a la base de datos
    private $conn;

    // Valores que estan permitidos
    public const PRIORIDADES = ['Baja', 'Media', 'Alta'];
    public const ESTADOS = ['Abierta', 'En curso', 'Resuelta'];

    // Propiedades 
    public $id;
    public $titulo;
    public $aula;
    public $prioridad;
    public $estado;
    public $descripcion;
    public $usuario_id;
    public $fecha_creacion;

    /**
     * Constructor: recibe la conexión PDO
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * mira los datos y devuelve un array de errores si lo shay
     */
    public function validar()
    {
        $errores = [];
        if (empty($this->titulo)) {
            $errores[] = 'El titulo es obligatorio';
        }
        if (empty($this->aula)) {
            $errores[] = 'El aula es obligatoria';
        }
        if (!in_array($this->prioridad, self::PRIORIDADES)) {
            $errores[] = 'La prioridad es incorrecto';
        }
        if (!in_array($this->estado, self::ESTADOS)) {
            $errores[] = 'El estado es incorrecto';
        }
        return $errores;
    }

    /**
     * Devuelve las incidencias de un usuario
     */
    public function listarPorUsuario($usuario_id, $prioridad = null)
    {
        $sql = "SELECT * FROM incidencias WHERE usuario_id = :usuario_id";
        if (!empty($prioridad)) {
            $sql .= " AND prioridad = :prioridad";
        }
        $sql .= " ORDER BY fecha_creacion DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuario_id);
        if (!empty($prioridad)) {
            $stmt->bindParam(':prioridad', $prioridad);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * cuenta las incidencias abiertas de un usuario
     */
    public function contarAbiertas($usuario_id)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM incidencias WHERE usuario_id = :usuario_id AND estado = 'Abierta'");
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        $fila = $stmt->fetch();
        return $fila['total'];
    }

    /**
     * Devuelve una incidencia del usuario, o false si no existe o no es suya
     */
    public function obtenerPorId($id, $usuario_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM incidencias WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Inserta la incidencia
     */
    public function crear()
    {
        $stmt = $this->conn->prepare("INSERT INTO incidencias (titulo, aula, prioridad, estado, descripcion, usuario_id)
                                      VALUES (:titulo, :aula, :prioridad, :estado, :descripcion, :usuario_id)");
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':aula', $this->aula);
        $stmt->bindParam(':prioridad', $this->prioridad);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':usuario_id', $this->usuario_id);
        return $stmt->execute();
    }

    /**
     * Actualiza la incidencia, solo si es del usuario
     */
    public function actualizar()
    {
        $stmt = $this->conn->prepare("UPDATE incidencias SET titulo = :titulo, aula = :aula, prioridad = :prioridad,
                                      estado = :estado, descripcion = :descripcion
                                      WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':aula', $this->aula);
        $stmt->bindParam(':prioridad', $this->prioridad);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':usuario_id', $this->usuario_id);
        return $stmt->execute();
    }

    /**
     * Elimina la incidencia sabiendo el usuario
     */
    public function eliminar($id, $usuario_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM incidencias WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }



    public function obtenerTodas($estado = null)
    {
        $sql = "SELECT * FROM incidencias";
        if (!empty($estado)) {
            $sql .= " WHERE estado = :estado";
        }
        $sql .= " ORDER BY id";

        $stmt = $this->conn->prepare($sql);
        if (!empty($estado)) {
            $stmt->bindParam(':estado', $estado);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * las incidencias por su id da falso si no existe
     */
    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM incidencias WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * actualiza una incidencia por su id
     */
    public function actualizarApi()
    {
        $stmt = $this->conn->prepare("UPDATE incidencias SET titulo = :titulo, aula = :aula, prioridad = :prioridad,
                                      estado = :estado, descripcion = :descripcion WHERE id = :id");
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':aula', $this->aula);
        $stmt->bindParam(':prioridad', $this->prioridad);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    /**
     * elimina una incidencia por su id
     */
    public function eliminarApi($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM incidencias WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
