<?php
/**
 * API REST para Gestión de Colmenas
 * Punto de entrada único - Enrutador (Front Controller)
 * 
 * Este archivo gestiona todas las peticiones HTTP y las dirige
 * al controlador apropiado según el método y la ruta
 * 
 * Estructura de rutas:
 * GET    /api/colmenas        - Obtener todas las colmenas
 * GET    /api/colmenas/{id}   - Obtener una colmena específica
 * POST   /api/colmenas        - Crear una nueva colmena
 * PUT    /api/colmenas/{id}   - Actualizar una colmena
 * DELETE /api/colmenas/{id}   - Eliminar una colmena
 */

// Cargar configuración
require_once '../config/config.php';
require_once '../config/Database.php';
require_once '../models/Colmena.php';
require_once '../controllers/ColmenaController.php';

// Obtener el método HTTP de la petición
$metodo = $_SERVER['REQUEST_METHOD'];

// Obtener la URI y limpiarla
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

// Separar la URI en segmentos
$uri_segmentos = explode('/', $uri);

// Obtener la base de la URL (útil para diferentes entornos)
// Ejemplo: /api-colmenas/public/api/colmenas
// Necesitamos extraer el recurso (colmenas) y el ID si existe

// Buscar el índice donde aparece 'api'
$api_index = array_search('api', $uri_segmentos);

// Si no encontramos 'api' en la ruta, mostrar error
if ($api_index === false) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Ruta no encontrada. Use /api/colmenas'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Obtener el recurso (debe ser 'colmenas')
$recurso = $uri_segmentos[$api_index + 1] ?? null;

// Obtener el ID si existe
$id = $uri_segmentos[$api_index + 2] ?? null;

// Verificar que el recurso sea 'colmenas'
if ($recurso !== 'colmenas') {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Recurso no encontrado. Use /api/colmenas'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Conectar a la base de datos
$database = new Database();
$db = $database->getConnection();

// Verificar conexión
if ($db === null) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Instanciar el controlador
$colmenaController = new ColmenaController($db);

// Enrutar según el método HTTP y los parámetros
try {
    switch ($metodo) {
        case 'GET':
            if ($id) {
                // GET /api/colmenas/{id} - Obtener una colmena específica
                $colmenaController->obtenerPorId($id);
            } else {
                // GET /api/colmenas - Obtener todas las colmenas
                $colmenaController->obtenerTodas();
            }
            break;

        case 'POST':
            // POST /api/colmenas - Crear nueva colmena
            if ($id) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'No se debe especificar ID en POST. Use PUT para actualizar.'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                $colmenaController->crear();
            }
            break;

        case 'PUT':
            // PUT /api/colmenas/{id} - Actualizar colmena
            if ($id) {
                $colmenaController->actualizar($id);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Debe especificar el ID de la colmena a actualizar'
                ], JSON_UNESCAPED_UNICODE);
            }
            break;

        case 'DELETE':
            // DELETE /api/colmenas/{id} - Eliminar colmena
            if ($id) {
                $colmenaController->eliminar($id);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Debe especificar el ID de la colmena a eliminar'
                ], JSON_UNESCAPED_UNICODE);
            }
            break;

        default:
            // Método no soportado
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
