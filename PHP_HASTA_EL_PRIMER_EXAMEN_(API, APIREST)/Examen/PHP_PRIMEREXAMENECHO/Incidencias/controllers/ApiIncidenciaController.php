<?php
/**
 * Controlador de la API REST de incidencias
 */
class ApiIncidenciaController
{
    private $incidencia;
    private $usuario;

    public function __construct($db)
    {
        $this->incidencia = new Incidencia($db);
        $this->usuario = new Usuario($db);
    }

    public function obtenerTodas()
    {
        $estado = $_GET['estado'] ?? null;

        if ($estado && !in_array($estado, Incidencia::ESTADOS)) {
            $this->respuesta(400, ['success' => false, 'message' => 'Estado no válido. Use Abierta, En curso o Resuelta']);
        }

        $incidencias = $this->incidencia->obtenerTodas($estado);
        $this->respuesta(200, [
            'success' => true,
            'total' => count($incidencias),
            'data' => $incidencias
        ]);
    }

    /**
     * GET /api/incidencias/el id
     */
    public function obtenerPorId($id)
    {
        $incidencia = $this->incidencia->buscarPorId($id);

        if ($incidencia) {
            $this->respuesta(200, ['success' => true, 'data' => $incidencia]);
        } else {
            $this->respuesta(404, ['success' => false, 'message' => 'Incidencia no encontrada']);
        }
    }

    /**
     * POST /api/incidencias
     */
    public function crear()
    {
        $data = json_decode(file_get_contents("php://input"));
        if (empty($data)) {
            $this->respuesta(400, ['success' => false, 'message' => 'No se recibieron datos o el JSON no es válido']);
        }

        $this->incidencia->titulo = $data->titulo ?? '';
        $this->incidencia->aula = $data->aula ?? '';
        $this->incidencia->prioridad = $data->prioridad ?? 'Media';
        $this->incidencia->estado = $data->estado ?? 'Abierta';
        $this->incidencia->descripcion = $data->descripcion ?? '';
        $this->incidencia->usuario_id = $data->usuario_id ?? '';

        $errores = $this->incidencia->validar();
        if (!$this->usuario->existeId($this->incidencia->usuario_id)) {
            $errores[] = 'El usuario_id no existe';
        }
        if (!empty($errores)) {
            $this->respuesta(400, ['success' => false, 'message' => 'Error de validación', 'errores' => $errores]);
        }

        $this->incidencia->crear();
        $this->respuesta(201, ['success' => true, 'message' => 'Incidencia creada correctamente']);
    }

    /**
     * PUT /api/incidencias/el id
     */
    public function actualizar($id)
    {
        $actual = $this->incidencia->buscarPorId($id);
        if (!$actual) {
            $this->respuesta(404, ['success' => false, 'message' => 'Incidencia no encontrada']);
        }

        $data = json_decode(file_get_contents("php://input"));
        if (empty($data)) {
            $this->respuesta(400, ['success' => false, 'message' => 'No se recibieron datos o el JSON no es válido']);
        }

        $this->incidencia->id = $id;
        $this->incidencia->titulo = $data->titulo ?? $actual['titulo'];
        $this->incidencia->aula = $data->aula ?? $actual['aula'];
        $this->incidencia->prioridad = $data->prioridad ?? $actual['prioridad'];
        $this->incidencia->estado = $data->estado ?? $actual['estado'];
        $this->incidencia->descripcion = $data->descripcion ?? $actual['descripcion'];

        $errores = $this->incidencia->validar();
        if (!empty($errores)) {
            $this->respuesta(400, ['success' => false, 'message' => 'Error de validación', 'errores' => $errores]);
        }

        $this->incidencia->actualizarApi();
        $this->respuesta(200, ['success' => true, 'message' => 'Incidencia actualizada correctamente']);
    }

    /**
     * DELETE /api/incidencias/el id
     */
    public function eliminar($id)
    {
        if (!$this->incidencia->buscarPorId($id)) {
            $this->respuesta(404, ['success' => false, 'message' => 'Incidencia no encontrada']);
        }

        $this->incidencia->eliminarApi($id);
        $this->respuesta(200, ['success' => true, 'message' => 'Incidencia eliminada correctamente']);
    }

    /**
     * Envia una respuesta JSON con su código HTTP y termina
     */
    private function respuesta($codigo, $datos)
    {
        http_response_code($codigo);
        echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}
