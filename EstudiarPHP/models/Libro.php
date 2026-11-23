<?php
/**
 * Modelo Libro
 * Gestiona las operaciones sobre la tabla libros
 */
class Libro
{
    // Conexión a la base de datos
    private $conn;

    // Propiedades que corresponden a las columnas de la tabla
    public $id;
    public $titulo;
    public $autor;
    public $genero;
    public $anio_publicacion;   // columna `año_publicacion`
    public $leido;
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
     * Valida los datos del libro y devuelve un array de errores
     */
    public function validar()
    {
        $errores = [];
        if (empty($this->titulo)) {
            $errores[] = 'El título es obligatorio';
        }
        if (empty($this->autor)) {
            $errores[] = 'El autor es obligatorio';
        }
        $generos = ['Ficción', 'No Ficción', 'Ciencia', 'Historia', 'Biografía', 'Otro'];
        if (!in_array($this->genero, $generos)) {
            $errores[] = 'El género no es válido';
        }
        if ($this->anio_publicacion !== '' && !is_numeric($this->anio_publicacion)) {
            $errores[] = 'El año debe ser un número';
        }
        return $errores;
    }

    /**
     * Devuelve todos los libros de un usuario
     */
    public function listarPorUsuario($usuario_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM libros WHERE usuario_id = :usuario_id");
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Carga un libro solo si pertenece al usuario
     * Devuelve true si lo encuentra y rellena las propiedades
     */
    public function obtenerPorId($id, $usuario_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM libros WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        $fila = $stmt->fetch();

        if ($fila) {
            $this->id = $fila['id'];
            $this->titulo = $fila['titulo'];
            $this->autor = $fila['autor'];
            $this->genero = $fila['genero'];
            $this->anio_publicacion = $fila['año_publicacion'];
            $this->leido = $fila['leido'];
            $this->usuario_id = $fila['usuario_id'];
            $this->fecha_creacion = $fila['fecha_creacion'];
            return true;
        }
        return false;
    }

    /**
     * Inserta el libro en la base de datos
     */
    public function crear()
    {
        $stmt = $this->conn->prepare("INSERT INTO libros (titulo, autor, genero, `año_publicacion`, leido, usuario_id)
                                      VALUES (:titulo, :autor, :genero, :anio, :leido, :usuario_id)");
        $anio = $this->anio_publicacion === '' ? null : $this->anio_publicacion;
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':autor', $this->autor);
        $stmt->bindParam(':genero', $this->genero);
        $stmt->bindParam(':anio', $anio);
        $stmt->bindParam(':leido', $this->leido);
        $stmt->bindParam(':usuario_id', $this->usuario_id);
        return $stmt->execute();
    }

    /**
     * Actualiza el libro, solo si pertenece al usuario
     */
    public function actualizar()
    {
        $stmt = $this->conn->prepare("UPDATE libros SET titulo = :titulo, autor = :autor, genero = :genero,
                                      `año_publicacion` = :anio, leido = :leido
                                      WHERE id = :id AND usuario_id = :usuario_id");
        $anio = $this->anio_publicacion === '' ? null : $this->anio_publicacion;
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':autor', $this->autor);
        $stmt->bindParam(':genero', $this->genero);
        $stmt->bindParam(':anio', $anio);
        $stmt->bindParam(':leido', $this->leido);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':usuario_id', $this->usuario_id);
        return $stmt->execute();
    }

    /**
     * Elimina un libro verificando que pertenece al usuario
     * Devuelve true solo si se ha borrado
     */
    public function eliminar($id, $usuario_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM libros WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}