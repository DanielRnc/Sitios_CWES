<?php
/**
 * INDEX.PHP - Punto de entrada de la aplicación
 * 
 * Este archivo es el controlador frontal (Front Controller) de la aplicación.
 * Todas las peticiones pasan por aquí y se enrutan a los controladores correspondientes.
 */

// Iniciamos la sesión para gestionar usuarios logueados
session_start();

// Incluimos los archivos necesarios
require_once 'config/database.php';
require_once 'app/models/Usuario.php';
require_once 'app/models/Tarea.php';
require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/TareaController.php';
require_once 'app/controllers/PublicController.php';

/**
 * ENRUTADOR SIMPLE
 * 
 * Lee el parámetro 'action' de la URL para determinar qué acción ejecutar
 * Ejemplo: index.php?action=login
 */

// Obtenemos la acción de la URL, si no existe usamos 'home' por defecto
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

// Instanciamos los controladores que vamos a necesitar
$authController = new AuthController();
$tareaController = new TareaController();
$publicController = new PublicController();

/**
 * SWITCH para enrutar las peticiones
 * Cada caso llama al método correspondiente del controlador adecuado
 */
switch($action) {
    
    // ========== RUTAS PÚBLICAS ==========
    case 'home':
        $publicController->index();
        break;
    
    // ========== RUTAS DE AUTENTICACIÓN ==========
    case 'login':
        $authController->mostrarLogin();
        break;
    
    case 'procesarLogin':
        $authController->procesarLogin();
        break;
    
    case 'register':
        $authController->mostrarRegister();
        break;
    
    case 'procesarRegister':
        $authController->procesarRegister();
        break;
    
    case 'logout':
        $authController->logout();
        break;
    
    // ========== RUTAS DE TAREAS (requieren autenticación) ==========
    case 'misTareas':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $tareaController->misTareas();
        break;
    
    case 'crearTarea':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $tareaController->crear();
        break;
    
    case 'guardarTarea':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $tareaController->guardar();
        break;
    
    case 'editarTarea':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $tareaController->editar();
        break;
    
    case 'actualizarTarea':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $tareaController->actualizar();
        break;
    
    case 'eliminarTarea':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $tareaController->eliminar();
        break;
    
    // ========== RUTA POR DEFECTO (404) ==========
    default:
        echo "<h1>Página no encontrada</h1>";
        echo "<a href='index.php'>Volver al inicio</a>";
        break;
}
?>
