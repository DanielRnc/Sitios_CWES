<?php

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Incidencia.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../controllers/ApiIncidenciaController.php';

$metodo = $_SERVER['REQUEST_METHOD'];

$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$uri_segmentos = explode('/', $uri);

// Se busca el recurso y se coge el id que va detrás
$recurso_index = array_search('incidencias', $uri_segmentos);

if ($recurso_index === false) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Recurso no encontrado. Use /api/incidencias'], JSON_UNESCAPED_UNICODE);
    exit;
}

$id = $uri_segmentos[$recurso_index + 1] ?? null;

$db = (new Database())->getConexion();
if ($db === null) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de conexión'], JSON_UNESCAPED_UNICODE);
    exit;
}

$controller = new ApiIncidenciaController($db);

try {
    switch ($metodo) {
        case 'GET':
            if ($id) {
                $controller->obtenerPorId($id);
            } else {
                $controller->obtenerTodas();   
            }
            break;

        case 'POST':
            $controller->crear();
            break;

        case 'PUT':
            $controller->actualizar($id);
            break;

        case 'DELETE':
            $controller->eliminar($id);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'error http no permitido'], JSON_UNESCAPED_UNICODE);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error dentro del servidor', 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
