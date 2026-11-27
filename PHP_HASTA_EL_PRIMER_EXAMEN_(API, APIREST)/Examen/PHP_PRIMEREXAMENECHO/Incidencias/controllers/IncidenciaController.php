<?php
/**
 * IncidenciaController
 */
class IncidenciaController
{
    private $incidencia;

    /**
     * protege todas las acciones y crea el modelo
     */
    public function __construct($db)
    {
        // Protección de rutas: sin sesión no se entra
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?operacion=login');
            exit;
        }
        $this->incidencia = new Incidencia($db);
    }

    /**
     * muestra las incidencias del usuario
     */
    public function listar()
    {
        $prioridad = $_GET['prioridad'] ?? '';
        $incidencias = $this->incidencia->listarPorUsuario($_SESSION['usuario_id'], $prioridad);
        /*
        *no lo pide pero lo pongo $abiertas para luego en el listar mostrar cuantas estan abiertas
        */
        $abiertas = $this->incidencia->contarAbiertas($_SESSION['usuario_id']);
        require 'views/incidencias/listar.php';
    }

    /**
     * muestra el formulario y, si llega por POST, guarda la incidencia
     */
    public function crear()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (empty($data)) {
            $this->respuesta(400, ['success' => false, 'message' => 'No se recibieron datos o el JSON no es válido']);
        }

        $this->incidencia->titulo = $data->titulo ?? '';
        $this->incidencia->aula = $data->aula ?? '';
        $this->incidencia->prioridad = $data->prioridad ?? 'Media';
        $this->incidencia->estado = $data->estado ?? 'Abierta';
        $this->incidencia->descripcion = $data->descripcion ?? '';
        $this->incidencia->usuario_id = $data->usuario_id ?? '';

        $errores = $this->incidencia->validar();
        if (!$this->usuario->existeId($this->incidencia->usuario_id)) {
            $errores[] = 'El usuario_id no existe';
        }
        if (!empty($errores)) {
            $this->respuesta(400, ['success' => false, 'message' => 'Error de validación', 'errores' => $errores]);
        }

        $this->incidencia->crear();
        $this->respuesta(201, ['success' => true, 'message' => 'Incidencia creada correctamente']);
    }

    /**
     * carga la incidencia en el formulario y, si llega por POST, la actualiza
     */
    public function editar()
    {
        $errores = [];
        $id = $_GET['id'];

        // Carga la incidencia solo si es del usuario logueado
        $incidencia = $this->incidencia->obtenerPorId($id, $_SESSION['usuario_id']);
        if (!$incidencia) {
            header('Location: index.php?operacion=listar');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->incidencia->id = $id;
            $this->incidencia->titulo = trim($_POST['titulo']);
            $this->incidencia->aula = trim($_POST['aula']);
            $this->incidencia->prioridad = $_POST['prioridad'];
            $this->incidencia->estado = $_POST['estado'];
            $this->incidencia->descripcion = trim($_POST['descripcion']);
            $this->incidencia->usuario_id = $_SESSION['usuario_id'];

            $errores = $this->incidencia->validar();

            if (empty($errores)) {
                $this->incidencia->actualizar();
                header('Location: index.php?operacion=listar');
                exit;
            }
        }

        require 'views/incidencias/editar.php';
    }

    /**
     * borra la incidencia si es del usuario y vuelve al listado
     */
    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->incidencia->eliminar($_POST['id'], $_SESSION['usuario_id']);
        }
        header('Location: index.php?operacion=listar');
        exit;
    }

}
