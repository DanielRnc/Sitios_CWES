<?php
/**
 * Enrutador principal
 * Todas las peticiones pasan por aquí por el get
 */
session_start();

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'models/Usuario.php';
require_once 'models/Incidencia.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/IncidenciaController.php';

// Conexión a la base de datos
$db = (new Database())->getConexion();

// lo que se va a pedir
$action = $_GET['operacion'] ?? 'login';

switch ($action) {
    case 'registro':
        $controller = new AuthController($db);
        $controller->registro();
        break;

    case 'login':
        $controller = new AuthController($db);
        $controller->login();
        break;

    case 'logout':
        $controller = new AuthController($db);
        $controller->logout();
        break;

    case 'listar':
        $controller = new IncidenciaController($db);
        $controller->listar();
        break;

    case 'crear':
        $controller = new IncidenciaController($db);
        $controller->crear();
        break;

    case 'editar':
        $controller = new IncidenciaController($db);
        $controller->editar();
        break;

    case 'eliminar':
        $controller = new IncidenciaController($db);
        $controller->eliminar();
        break;

    case 'Reasignar':
        $controller = new IncidenciaController($db);
        $controller->reasignar();
        break;
        /*
        *no me da tiempo a terminar el reasignar
        */
    default:
        echo 'Página no encontrada';
        break;
}
