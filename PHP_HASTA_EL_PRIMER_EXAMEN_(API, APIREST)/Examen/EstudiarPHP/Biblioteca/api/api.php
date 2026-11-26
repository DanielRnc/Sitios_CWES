<?php
/**
 * API REST de libros. Backend puro: solo devuelve JSON
 * Punto de entrada único de la API, igual que en API-Colmenas
 *
 * Apartado E - CRUD completo:
 *   GET    /api/libros         -> todos los libros
 *   GET    /api/libros/{id}    -> un libro
 *   POST   /api/libros         -> crear un libro
 *   PUT    /api/libros/{id}    -> actualizar un libro
 *   DELETE /api/libros/{id}    -> eliminar un libro
 *
 * Apartado F - Transferir:
 *   ENDPOINT: PATCH /api/libros/{id}/transferir
 *   Cuerpo:   {"usuario_id": 2}
 *   Cualquier usuario puede transferir cualquier libro a otro usuario del sistema
 *
 * Si el servidor no tiene activado el .htaccess, las mismas rutas
 * funcionan como /api/api.php/libros, /api/api.php/libros/{id}...
 */
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Libro.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../controllers/ApiLibroController.php';

// Método HTTP de la petición
$metodo = $_SERVER['REQUEST_METHOD'];

// Ruta pedida, sin parámetros, separada en trozos
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
$uri_segmentos = explode('/', $uri);

// Se busca el recurso 'libros' y se cogen el id y la acción que van detrás
$recurso_index = array_search('libros', $uri_segmentos);

if ($recurso_index === false) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Recurso no encontrado. Use /api/libros'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$id = $uri_segmentos[$recurso_index + 1] ?? null;
$accion = $uri_segmentos[$recurso_index + 2] ?? null;

// Conexión a la base de datos
$db = (new Database())->getConnection();

if ($db === null) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$controller = new ApiLibroController($db);

// Se elige el método del controlador según el verbo HTTP
try {
    switch ($metodo) {
        case 'GET':
            if ($id) {
                $controller->obtenerPorId($id);
            } else {
                $controller->obtenerTodos();
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

        case 'PATCH':
            // Apartado F: PATCH /api/libros/{id}/transferir
            if ($accion === 'transferir') {
                $controller->transferir($id);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Use PATCH /api/libros/{id}/transferir'
                ], JSON_UNESCAPED_UNICODE);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Método HTTP no permitido'
            ], JSON_UNESCAPED_UNICODE);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor',
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
