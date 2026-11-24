<?php
/**
 * LibroController
 * CRUD de libros del usuario logueado
 */
class LibroController
{
    private $libro;

    /**
     * Constructor: protege todas las acciones y crea el modelo Libro
     */
    public function __construct($db)
    {
        // Protección de rutas: sin sesión no se entra
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $this->libro = new LIbro($db);
    }
     /**
     * Listar: muestra los libros del usuario logueado
     */
    public function listar()
    {
        $libros = $this->libro->listarPorUsuario($_SESSION['usuario_id']);
        require 'views/libros/listar.php';
    }

    /**
     * Crear: muestra el formulario y, si llega por POST, guarda el libro
     */
    public function crear()
    {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->libro->titulo = trim($_POST['titulo']);
            $this->libro->autor = trim($_POST['autor']);
            $this->libro->genero = trim($_POST['genero']);
            $this->libro->anio_publicacion = $_POST['anio_publicacion'];
            $this->libro->leido = isset($_POST['leido']) ? 1 : 0;
            $this->libro->usuario_id = $_SESSION['usuario_id'];

            $errores = $this->libro->validar();

            // Si no hay errores se guarda y se vuelve al listado
            if (empty($errores)) {
                $this->libro->crear();
                header('Location: index.php?action=listar');
                exit;
            }
        }

        require 'views/libros/crear.php';
    }

        /**
     * Editar: carga el libro en el formulario y, si llega por POST, lo actualiza
     */
    public function editar()
    {
        $errores = [];
        $id = $_GET['id'];

        // Carga el libro solo si es del usuario logueado
        $libro = $this->libro->obtenerPorId($id, $_SESSION['usuario_id']);
        if (!$libro) {
            header('Location: index.php?action=listar');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->libro->id = $id;
            $this->libro->titulo = trim($_POST['titulo']);
            $this->libro->autor = trim($_POST['autor']);
            $this->libro->genero = $_POST['genero'];
            $this->libro->anio_publicacion = $_POST['anio_publicacion'];
            $this->libro->leido = isset($_POST['leido']) ? 1 : 0;
            $this->libro->usuario_id = $_SESSION['usuario_id'];

            $errores = $this->libro->validar();

            if (empty($errores)) {
                $this->libro->actualizar();
                header('Location: index.php?action=listar');
                exit;
            }
        }
        require 'views/libros/editar.php';
    }

        /**
     * Eliminar: borra el libro si es del usuario y vuelve al listado
     */
    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->libro->eliminar($_POST['id'], $_SESSION['usuario_id']);
        }
        header('Location: index.php?action=listar');
        exit;
    }
}
?>