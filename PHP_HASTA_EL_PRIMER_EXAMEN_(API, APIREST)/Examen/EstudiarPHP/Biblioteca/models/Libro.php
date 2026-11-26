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

       return $fila;
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

    /**
     * Apartado D: transfiere el libro a otro usuario
     * Solo se actualiza si el libro pertenece al usuario actual
     */
    public function transferir($id, $usuario_id, $destino_id)
    {
        $stmt = $this->conn->prepare("UPDATE libros SET usuario_id = :destino WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->bindParam(':destino', $destino_id);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Ejercicio: cambia leído a no leído y al revés, solo si el libro es del usuario
     * NOT leido invierte el valor: 0 pasa a 1 y 1 pasa a 0
     */
    public function cambiarLeido($id, $usuario_id)
    {
        $stmt = $this->conn->prepare("UPDATE libros SET leido = NOT leido WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        return $stmt->execute();
    }

    /* ===== Métodos usados por la API, sin comprobar el usuario dueño ===== */

    /**
     * Apartado E: devuelve todos los libros
     */
    public function obtenerTodos()
    {
        $stmt = $this->conn->prepare("SELECT * FROM libros ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Apartado E: devuelve un libro por su id, o false si no existe
     */
    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM libros WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Apartado E: actualiza un libro por su id
     */
    public function actualizarApi()
    {
        $stmt = $this->conn->prepare("UPDATE libros SET titulo = :titulo, autor = :autor, genero = :genero,
                                      `año_publicacion` = :anio, leido = :leido WHERE id = :id");
        $anio = $this->anio_publicacion === '' ? null : $this->anio_publicacion;
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':autor', $this->autor);
        $stmt->bindParam(':genero', $this->genero);
        $stmt->bindParam(':anio', $anio);
        $stmt->bindParam(':leido', $this->leido);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    /**
     * Apartado E: elimina un libro por su id
     */
    public function eliminarApi($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM libros WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Apartado F: transfiere cualquier libro a otro usuario
     */
    public function transferirApi($id, $destino_id)
    {
        $stmt = $this->conn->prepare("UPDATE libros SET usuario_id = :destino WHERE id = :id");
        $stmt->bindParam(':destino', $destino_id);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
