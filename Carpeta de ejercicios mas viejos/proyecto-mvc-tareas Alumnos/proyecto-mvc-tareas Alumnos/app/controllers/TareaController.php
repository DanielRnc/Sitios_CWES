<?php
/**
 * CONTROLADOR: TareaController
 * 
 * Este controlador maneja todas las operaciones CRUD sobre las tareas:
 * - Listar tareas del usuario
 * - Crear nueva tarea
 * - Editar tarea existente
 * - Eliminar tarea
 */

class TareaController {
    
    /**
     * Mostrar las tareas del usuario logueado
     */
    public function misTareas() {
        // Conectamos con la base de datos
        $database = new Database();
        $db = $database->getConnection();
        $tarea = new Tarea($db);
        
        // Obtenemos las tareas del usuario actual
        $tarea->usuario_id = $_SESSION['usuario_id'];
        $stmt = $tarea->obtenerPorUsuario();
        $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Cargamos la vista
        require_once 'app/views/user/mis_tareas.php';
    }
    
    /**
     * Mostrar el formulario para crear una nueva tarea
     */
    public function crear() {
        require_once 'app/views/user/crear_tarea.php';
    }
    
    /**
     * Guardar una nueva tarea en la base de datos
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Obtenemos los datos del formulario
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fase = $_POST['fase'];
            $estado = $_POST['estado'];
            
            // Validaciones
            if (empty($titulo) || empty($fase) || empty($estado)) {
                $_SESSION['error'] = "El título, fase y estado son obligatorios";
                header("Location: index.php?action=crearTarea");
                exit();
            }
            
            // Conectamos con la base de datos
            $database = new Database();
            $db = $database->getConnection();
            $tarea = new Tarea($db);
            
            // Asignamos los valores
            $tarea->titulo = $titulo;
            $tarea->descripcion = $descripcion;
            $tarea->fase = $fase;
            $tarea->estado = $estado;
            $tarea->usuario_id = $_SESSION['usuario_id'];
            
            // Guardamos la tarea
            if ($tarea->crear()) {
                $_SESSION['mensaje'] = "Tarea creada correctamente";
                header("Location: index.php?action=misTareas");
                exit();
            } else {
                $_SESSION['error'] = "Error al crear la tarea";
                header("Location: index.php?action=crearTarea");
                exit();
            }
        }
    }
    
    /**
     * Mostrar el formulario para editar una tarea
     */
    public function editar() {
        // Obtenemos el ID de la tarea a editar
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "ID de tarea no especificado";
            header("Location: index.php?action=misTareas");
            exit();
        }
        
        $id = $_GET['id'];
        
        // Conectamos con la base de datos
        $database = new Database();
        $db = $database->getConnection();
        $tarea = new Tarea($db);
        
        // Obtenemos la tarea
        $tarea->id = $id;
        if ($tarea->obtenerPorId()) {
            // Verificamos que la tarea pertenece al usuario actual
            if ($tarea->usuario_id != $_SESSION['usuario_id']) {
                $_SESSION['error'] = "No tienes permiso para editar esta tarea";
                header("Location: index.php?action=misTareas");
                exit();
            }
            
            // Cargamos la vista de edición
            require_once 'app/views/user/editar_tarea.php';
        } else {
            $_SESSION['error'] = "Tarea no encontrada";
            header("Location: index.php?action=misTareas");
            exit();
        }
    }
    
    /**
     * Actualizar una tarea existente
     */
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Obtenemos los datos del formulario
            $id = $_POST['id'];
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fase = $_POST['fase'];
            $estado = $_POST['estado'];
            
            // Validaciones
            if (empty($titulo) || empty($fase) || empty($estado)) {
                $_SESSION['error'] = "El título, fase y estado son obligatorios";
                header("Location: index.php?action=editarTarea&id=" . $id);
                exit();
            }
            
            // Conectamos con la base de datos
            $database = new Database();
            $db = $database->getConnection();
            $tarea = new Tarea($db);
            
            // Asignamos los valores
            $tarea->id = $id;
            $tarea->titulo = $titulo;
            $tarea->descripcion = $descripcion;
            $tarea->fase = $fase;
            $tarea->estado = $estado;
            $tarea->usuario_id = $_SESSION['usuario_id'];
            
            // Actualizamos la tarea
            if ($tarea->actualizar()) {
                $_SESSION['mensaje'] = "Tarea actualizada correctamente";
                header("Location: index.php?action=misTareas");
                exit();
            } else {
                $_SESSION['error'] = "Error al actualizar la tarea";
                header("Location: index.php?action=editarTarea&id=" . $id);
                exit();
            }
        }
    }
    
    /**
     * Eliminar una tarea
     */
    public function eliminar() {
        // Obtenemos el ID de la tarea a eliminar
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = "ID de tarea no especificado";
            header("Location: index.php?action=misTareas");
            exit();
        }
        
        $id = $_GET['id'];
        
        // Conectamos con la base de datos
        $database = new Database();
        $db = $database->getConnection();
        $tarea = new Tarea($db);
        
        // Asignamos los valores
        $tarea->id = $id;
        $tarea->usuario_id = $_SESSION['usuario_id'];
        
        // Eliminamos la tarea
        if ($tarea->eliminar()) {
            $_SESSION['mensaje'] = "Tarea eliminada correctamente";
        } else {
            $_SESSION['error'] = "Error al eliminar la tarea";
        }
        
        header("Location: index.php?action=misTareas");
        exit();
    }
}
?>
