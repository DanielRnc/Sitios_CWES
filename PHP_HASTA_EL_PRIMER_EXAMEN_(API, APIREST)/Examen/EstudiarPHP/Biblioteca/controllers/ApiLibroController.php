<?php
/**
 * ApiLibroController
 * Controlador de la API REST de libros: no tiene vistas, responde en JSON
 * Sigue el mismo esquema que ColmenaController de API-Colmenas
 */
class ApiLibroController
{
    private $libro;
    private $usuario;

    /**
     * Constructor: crea los modelos con la conexión
     */
    public function __construct($db)
    {
        $this->libro = new Libro($db);
        $this->usuario = new Usuario($db);
    }

    /**
     * GET /api/libros
     * Devuelve todos los libros
     */
    public function obtenerTodos()
    {
        $libros = $this->libro->obtenerTodos();
        $this->respuesta(200, [
            'success' => true,
            'total' => count($libros),
            'data' => $libros
        ]);
    }

    /**
     * GET /api/libros/{id}
     * Devuelve un libro
     */
    public function obtenerPorId($id)
    {
        $libro = $this->libro->buscarPorId($id);

        if ($libro) {
            $this->respuesta(200, ['success' => true, 'data' => $libro]);
        } else {
            $this->respuesta(404, ['success' => false, 'message' => 'Libro no encontrado']);
        }
    }

    /**
     * POST /api/libros
     * Crea un libro con los datos JSON del cuerpo
     * Ejemplo: {"titulo":"Dune","autor":"Herbert","genero":"Ficción","anio_publicacion":1965,"leido":0,"usuario_id":1}
     */
    public function crear()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data)) {
            $this->respuesta(400, ['success' => false, 'message' => 'No se recibieron datos o el JSON no es válido']);
        }

        $this->libro->titulo = $data->titulo ?? '';
        $this->libro->autor = $data->autor ?? '';
        $this->libro->genero = $data->genero ?? 'Otro';
        $this->libro->anio_publicacion = $data->anio_publicacion ?? '';
        $this->libro->leido = !empty($data->leido) ? 1 : 0;
        $this->libro->usuario_id = $data->usuario_id ?? '';

        $errores = $this->libro->validar();
        if (!$this->usuario->existeId($this->libro->usuario_id)) {
            $errores[] = 'El usuario_id no existe';
        }

        if (!empty($errores)) {
            $this->respuesta(400, ['success' => false, 'message' => 'Error de validación', 'errores' => $errores]);
        }

        $this->libro->crear();
        $this->respuesta(201, ['success' => true, 'message' => 'Libro creado correctamente']);
    }

    /**
     * PUT /api/libros/{id}
     * Actualiza un libro con los datos JSON del cuerpo
     */
    public function actualizar($id)
    {
        $libro = $this->libro->buscarPorId($id);
        if (!$libro) {
            $this->respuesta(404, ['success' => false, 'message' => 'Libro no encontrado']);
        }

        $data = json_decode(file_get_contents("php://input"));
        if (empty($data)) {
            $this->respuesta(400, ['success' => false, 'message' => 'No se recibieron datos o el JSON no es válido']);
        }

        // Si un campo no llega se mantiene el que tenía
        $this->libro->id = $id;
        $this->libro->titulo = $data->titulo ?? $libro['titulo'];
        $this->libro->autor = $data->autor ?? $libro['autor'];
        $this->libro->genero = $data->genero ?? $libro['genero'];
        $this->libro->anio_publicacion = $data->anio_publicacion ?? $libro['año_publicacion'];
        $this->libro->leido = isset($data->leido) ? ($data->leido ? 1 : 0) : $libro['leido'];

        $errores = $this->libro->validar();
        if (!empty($errores)) {
            $this->respuesta(400, ['success' => false, 'message' => 'Error de validación', 'errores' => $errores]);
        }

        $this->libro->actualizarApi();
        $this->respuesta(200, ['success' => true, 'message' => 'Libro actualizado correctamente']);
    }

    /**
     * DELETE /api/libros/{id}
     * Elimina un libro
     */
    public function eliminar($id)
    {
        if (!$this->libro->buscarPorId($id)) {
            $this->respuesta(404, ['success' => false, 'message' => 'Libro no encontrado']);
        }

        $this->libro->eliminarApi($id);
        $this->respuesta(200, ['success' => true, 'message' => 'Libro eliminado correctamente']);
    }

    /**
     * Apartado F
     * ENDPOINT: PATCH /api/libros/{id}/transferir
     * Cuerpo: {"usuario_id": 2}
     * Cualquier usuario puede transferir cualquier libro a otro usuario del sistema
     */
    public function transferir($id)
    {
        $libro = $this->libro->buscarPorId($id);
        if (!$libro) {
            $this->respuesta(404, ['success' => false, 'message' => 'Libro no encontrado']);
        }

        $data = json_decode(file_get_contents("php://input"));
        $destino = $data->usuario_id ?? null;

        if (!$destino || !$this->usuario->existeId($destino)) {
            $this->respuesta(400, ['success' => false, 'message' => 'El usuario destino no existe']);
        }

        $this->libro->transferirApi($id, $destino);
        $this->respuesta(200, [
            'success' => true,
            'message' => 'Libro transferido correctamente',
            'de_usuario' => (int) $libro['usuario_id'],
            'a_usuario' => (int) $destino
        ]);
    }

    /**
     * Envía una respuesta JSON con su código HTTP y termina
     */
    private function respuesta($codigo, $datos)
    {
        http_response_code($codigo);
        echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}
