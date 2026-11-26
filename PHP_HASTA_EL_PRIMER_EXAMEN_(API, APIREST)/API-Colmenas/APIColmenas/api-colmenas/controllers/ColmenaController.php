<?php
/**
 * Controlador de Colmenas
 * Gestiona las peticiones HTTP y coordina el Modelo con las respuestas JSON
 * Este es el CONTROLADOR en el patrón MVC (sin vista, solo JSON)
 */

class ColmenaController {
    private $db;
    private $colmena;

    /**
     * Constructor
     * @param PDO $db Conexión a la base de datos
     */
    public function __construct($db) {
        $this->db = $db;
        $this->colmena = new Colmena($db);
    }

    /**
     * GET /colmenas
     * Obtiene todas las colmenas
     */
    public function obtenerTodas() {
        $stmt = $this->colmena->obtenerTodas();
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

            $this->respuesta(200, [
                'success' => true,
                'total' => $num,
                'data' => $colmenas_arr
            ]);
        } else {
            $this->respuesta(200, [
                'success' => true,
                'total' => 0,
                'data' => [],
                'message' => 'No se encontraron colmenas'
            ]);
        }
    }

    /**
     * GET /colmenas/{id}
     * Obtiene una colmena por su ID
     * @param int $id ID de la colmena
     */
    public function obtenerPorId($id) {
        if (empty($id) || !is_numeric($id)) {
            $this->respuesta(400, [
                'success' => false,
                'message' => 'ID inválido'
            ]);
            return;
        }

        if ($this->colmena->obtenerPorId($id)) {
            $colmena_item = [
                'id' => (int) $this->colmena->id,
                'zona' => $this->colmena->zona,
                'usuario_id' => (int) $this->colmena->usuario_id,
                'usuario_nombre' => $this->colmena->usuario_nombre,
                'fecha_creacion' => $this->colmena->fecha_creacion
            ];

            $this->respuesta(200, [
                'success' => true,
                'data' => $colmena_item
            ]);
        } else {
            $this->respuesta(404, [
                'success' => false,
                'message' => 'Colmena no encontrada'
            ]);
        }
    }

    /**
     * POST /colmenas
     * Crea una nueva colmena
     */
    public function crear() {
        // Obtener datos del cuerpo de la petición
        $data = json_decode(file_get_contents("php://input"));

        // Validar que se recibieron datos
        if (empty($data)) {
            $this->respuesta(400, [
                'success' => false,
                'message' => 'No se recibieron datos o formato JSON inválido'
            ]);
            return;
        }

        // Asignar valores al modelo
        $this->colmena->zona = $data->zona ?? '';
        $this->colmena->usuario_id = $data->usuario_id ?? '';

        // Validar datos
        $validacion = $this->colmena->validar();
        
        if (!$validacion['valido']) {
            $this->respuesta(400, [
                'success' => false,
                'message' => 'Error de validación',
                'errores' => $validacion['errores']
            ]);
            return;
        }

        // Intentar crear la colmena
        if ($this->colmena->crear()) {
            $this->respuesta(201, [
                'success' => true,
                'message' => 'Colmena creada correctamente',
                'data' => [
                    'id' => (int) $this->colmena->id,
                    'zona' => $this->colmena->zona,
                    'usuario_id' => (int) $this->colmena->usuario_id
                ]
            ]);
        } else {
            $this->respuesta(500, [
                'success' => false,
                'message' => 'Error al crear la colmena'
            ]);
        }
    }

    /**
     * PUT /colmenas/{id}
     * Actualiza una colmena existente
     * @param int $id ID de la colmena a actualizar
     */
    public function actualizar($id) {
        if (empty($id) || !is_numeric($id)) {
            $this->respuesta(400, [
                'success' => false,
                'message' => 'ID inválido'
            ]);
            return;
        }

        // Verificar que la colmena existe
        if (!$this->colmena->obtenerPorId($id)) {
            $this->respuesta(404, [
                'success' => false,
                'message' => 'Colmena no encontrada'
            ]);
            return;
        }

        // Obtener datos del cuerpo de la petición
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data)) {
            $this->respuesta(400, [
                'success' => false,
                'message' => 'No se recibieron datos o formato JSON inválido'
            ]);
            return;
        }

        // Asignar valores al modelo
        $this->colmena->id = $id;
        $this->colmena->zona = $data->zona ?? '';
        $this->colmena->usuario_id = $data->usuario_id ?? '';

        // Validar datos
        $validacion = $this->colmena->validar();
        
        if (!$validacion['valido']) {
            $this->respuesta(400, [
                'success' => false,
                'message' => 'Error de validación',
                'errores' => $validacion['errores']
            ]);
            return;
        }

        // Intentar actualizar la colmena
        if ($this->colmena->actualizar()) {
            $this->respuesta(200, [
                'success' => true,
                'message' => 'Colmena actualizada correctamente',
                'data' => [
                    'id' => (int) $this->colmena->id,
                    'zona' => $this->colmena->zona,
                    'usuario_id' => (int) $this->colmena->usuario_id
                ]
            ]);
        } else {
            $this->respuesta(500, [
                'success' => false,
                'message' => 'Error al actualizar la colmena'
            ]);
        }
    }

    /**
     * DELETE /colmenas/{id}
     * Elimina una colmena
     * @param int $id ID de la colmena a eliminar
     */
    public function eliminar($id) {
        if (empty($id) || !is_numeric($id)) {
            $this->respuesta(400, [
                'success' => false,
                'message' => 'ID inválido'
            ]);
            return;
        }

        // Verificar que la colmena existe
        if (!$this->colmena->obtenerPorId($id)) {
            $this->respuesta(404, [
                'success' => false,
                'message' => 'Colmena no encontrada'
            ]);
            return;
        }

        $this->colmena->id = $id;

        // Intentar eliminar la colmena
        if ($this->colmena->eliminar()) {
            $this->respuesta(200, [
                'success' => true,
                'message' => 'Colmena eliminada correctamente'
            ]);
        } else {
            $this->respuesta(500, [
                'success' => false,
                'message' => 'Error al eliminar la colmena'
            ]);
        }
    }

    /**
     * Envía una respuesta JSON con el código HTTP correspondiente
     * @param int $codigo Código HTTP de respuesta
     * @param array $datos Datos a enviar en formato JSON
     */
    private function respuesta($codigo, $datos) {
        http_response_code($codigo);
        echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}
