<?php

/**
 * Clase Database
 * Gestiona la conexión a la base de datos usando PDO
 * Patrón Singleton para una única instancia de conexión
 */

class Database
{
    private $host = 'localhost';
    private $db_name = 'api_colmenas';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    private $conn = null;

    /**
     * Obtiene la conexión a la base de datos
     * @return PDO|null Objeto PDO de conexión
     */
    public function getConnection()
    {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;

                $this->conn = new PDO($dsn, $this->username, $this->password);

                // Configurar PDO para lanzar excepciones en caso de error
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Configurar PDO para devolver arrays asociativos
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // En producción, no mostrar detalles del error
                error_log("Error de conexión: " . $e->getMessage());
                return null;
            }
        }
        return $this->conn;
    }
}

/**
 * Envía una respuesta JSON con el código HTTP correspondiente
 * @param int $codigo Código HTTP de respuesta
 * @param array $datos Datos a enviar en formato JSON
 */
function respuesta($codigo, $datos)
{
    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
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
// Acceso a la bd para traer todas las colmenas FROM " . $this->table_name . " c
$query = "SELECT 
                    c.id,
                    c.zona,
                    c.usuario_id,
                    c.fecha_creacion,
                    u.username,
                    u.nombre_completo as usuario_nombre
                  FROM " . "colmenas" . " c
                  LEFT JOIN usuarios u ON c.usuario_id = u.id
                  ORDER BY c.id DESC";

$stmt = $db->prepare($query);
$stmt->execute();

// Recorrido del recodSet devuelto por la consulta
$num = $stmt->rowCount();

if ($num > 0) {
    $colmenas_arr = [];

    while ($row = $stmt->fetch()) {
        $colmena_item = [
            'id' => (int) $row['id'],
            'zona' => $row['zona'],
            'usuario_id' => (int) $row['usuario_id'],
            'usuario_nombre' => $row['usuario_nombre'],
            'username' => $row['username'],
            'fecha_creacion' => $row['fecha_creacion']
        ];
        array_push($colmenas_arr, $colmena_item);
    }
    respuesta(200, [
        'success' => true,
        'total' => $num,
        'data' => $colmenas_arr
    ]);
} else {
    respuesta(200, [
        'success' => true,
        'total' => 0,
        'data' => [],
        'message' => 'No se encontraron colmenas'
    ]);
}
