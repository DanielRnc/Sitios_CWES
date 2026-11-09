FUNCIONES DE INTERÉS:
http_response_code(500);
json_encode([
'success' => false,
'message' => 'Error de conexión a la base de datos'
], JSON_UNESCAPED_UNICODE);

http_response_code(500);
echo json_encode([
'success' => false,
'message' => 'Error de conexión a la base de datos'
], JSON_UNESCAPED_UNICODE);