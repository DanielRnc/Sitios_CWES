<?php
/**
 * CONTROLADOR: PublicController
 * 
 * Este controlador maneja las páginas públicas de la aplicación
 * que no requieren autenticación
 */

class PublicController {
    
    /**
     * Mostrar la página de inicio con todas las tareas públicas
     */
    public function index() {
        // Conectamos con la base de datos
        $database = new Database();
        $db = $database->getConnection();
        $tarea = new Tarea($db);
        
        // Obtenemos todas las tareas
        $stmt = $tarea->obtenerTodas();
        $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Cargamos la vista pública
        require_once 'app/views/public/home.php';
    }
}
?>
