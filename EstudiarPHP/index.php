<?php
/**
 * Enrutador principal
 * Todas las peticiones pasan por aquí según $_GET['action']
 */
session_start();

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'models/Usuario.php';
require_once 'controllers/AuthController.php';
require_once 'models/Libro.php';
require_once 'controllers/LibroController.php';

// Conexión a la base de datos
$db = (new Database())->getConnection();

// Acción pedida, por defecto login
$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'registro':
        $controller = new AuthController($db);
        $controller->registro();
        break;
    case 'login':
        $controller = new AuthController($db);
        $controller->login();
        break;
    default:
        echo 'Página no encontrada';
        break;
    case 'logout':
        $controller = new AuthController($db);
        $controller->logout();
        break;
    
    case 'listar':
        $controller = new LibroController($db);
        $controller->listar();
        break;
    
    case 'crear':
        $controller = new LibroController($db);
        $controller->crear();
        break;
    
    case 'editar':
        $controller = new LibroController($db);
        $controller->editar();
        break;
    
    case 'eliminar':
        $controller = new LibroController($db);
        $controller->eliminar();
        break;
}
?>
