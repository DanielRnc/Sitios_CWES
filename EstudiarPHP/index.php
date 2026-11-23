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

// Conexión a la base de datos
$db = (new Database())->getConnection();

// Acción pedida, por defecto login
$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'registro':
        $controller = new AuthController($db);
        $controller->registro();
        break;
    
    default:
        echo 'Página no encontrada';
        break;
}
?>
