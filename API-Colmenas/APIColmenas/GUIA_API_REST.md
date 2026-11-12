# GUÍA DIDÁCTICA: APIs REST y el Patrón MVC

## 🎓 ¿Qué es una API REST?

**REST** (Representational State Transfer) es un estilo arquitectónico para diseñar servicios web que permite la comunicación entre sistemas usando HTTP.

### Principios REST

1. **Cliente-Servidor**: Separación entre quien consume (cliente) y quien provee (servidor)
2. **Sin Estado**: Cada petición contiene toda la información necesaria
3. **Cacheable**: Las respuestas deben indicar si pueden ser cacheadas
4. **Interfaz Uniforme**: URLs consistentes y predecibles
5. **Sistema en Capas**: El cliente no necesita saber la estructura interna

## 🔗 Recursos y Endpoints

### ¿Qué es un Recurso?

Un **recurso** es cualquier entidad que queremos exponer en la API.

En nuestro caso: **colmena**

### ¿Qué es un Endpoint?

Un **endpoint** es una URL específica que da acceso a un recurso.

Ejemplos:
- `/api/colmenas` - Colección de colmenas
- `/api/colmenas/1` - Colmena específica con ID 1

## 📡 Métodos HTTP (Verbos)

| Método | Operación CRUD | Uso | Idempotente |
|--------|---------------|-----|-------------|
| GET | Read | Obtener recursos | ✅ Sí |
| POST | Create | Crear nuevos recursos | ❌ No |
| PUT | Update | Actualizar recursos | ✅ Sí |
| DELETE | Delete | Eliminar recursos | ✅ Sí |
| PATCH | Update parcial | Actualizar campos específicos | ❌ No |

### Idempotencia

Una operación es **idempotente** si ejecutarla múltiples veces produce el mismo resultado.

- `GET /colmenas/1` - Llamarlo 10 veces devuelve lo mismo ✅
- `DELETE /colmenas/1` - Llamarlo 10 veces, solo elimina una vez ✅
- `POST /colmenas` - Llamarlo 10 veces crea 10 colmenas diferentes ❌

## 🎯 Diseño de Endpoints RESTful

### ✅ BUENAS PRÁCTICAS

```
GET    /api/colmenas          # Listar todas
GET    /api/colmenas/1        # Obtener una
POST   /api/colmenas          # Crear nueva
PUT    /api/colmenas/1        # Actualizar completa
DELETE /api/colmenas/1        # Eliminar
```

### ❌ MALAS PRÁCTICAS

```
GET  /api/getColmenas          # No usar verbos en URLs
POST /api/crearColmena         # No usar verbos
GET  /api/colmenas?delete=1    # No usar GET para eliminar
POST /api/colmenas/update/1    # No usar POST para actualizar
```

### Reglas de Oro

1. **URLs son sustantivos**, métodos HTTP son verbos
2. Usar **plural** para colecciones: `/colmenas` no `/colmena`
3. Usar **minúsculas** y **guiones**: `/api/colmenas-activas`
4. Anidar recursos relacionados: `/usuarios/1/colmenas`
5. Versionar la API: `/api/v1/colmenas`

## 📊 Códigos de Estado HTTP

### 2xx - Éxito
- **200 OK**: Petición exitosa (GET, PUT, DELETE)
- **201 Created**: Recurso creado (POST)
- **204 No Content**: Exitoso pero sin contenido

### 4xx - Error del Cliente
- **400 Bad Request**: Datos inválidos
- **401 Unauthorized**: No autenticado
- **403 Forbidden**: Autenticado pero sin permisos
- **404 Not Found**: Recurso no existe
- **405 Method Not Allowed**: Método HTTP no soportado

### 5xx - Error del Servidor
- **500 Internal Server Error**: Error genérico del servidor
- **503 Service Unavailable**: Servidor no disponible temporalmente

## 📦 Formato JSON

### Estructura de Respuestas

#### Respuesta Exitosa
```json
{
    "success": true,
    "data": {
        "id": 1,
        "zona": "Toledo",
        "usuario_id": 1
    }
}
```

#### Respuesta con Lista
```json
{
    "success": true,
    "total": 10,
    "data": [
        { "id": 1, "zona": "Toledo" },
        { "id": 2, "zona": "Madrid" }
    ]
}
```

#### Respuesta de Error
```json
{
    "success": false,
    "message": "Error de validación",
    "errores": [
        "El campo 'zona' es obligatorio",
        "El campo 'usuario_id' debe ser numérico"
    ]
}
```

## 🏗️ MVC en una API REST

### Diferencias con MVC Web Tradicional

| Aspecto | MVC Web | MVC API |
|---------|---------|---------|
| Vista | HTML/PHP | JSON |
| Respuesta | Página web | Datos estructurados |
| Formularios | HTML Forms | JSON Body |
| Navegación | Links/Redirects | URLs directas |

### Flujo en API REST

```
1. Cliente hace petición HTTP
   POST /api/colmenas
   Body: {"zona": "Toledo", "usuario_id": 1}
   ↓
2. ENRUTADOR (api.php) analiza petición
   - Método: POST
   - Recurso: colmenas
   - ID: ninguno
   ↓
3. CONTROLADOR recibe petición
   ColmenaController->crear()
   - Lee JSON del body
   - Valida formato
   ↓
4. CONTROLADOR llama al MODELO
   $colmena->zona = "Toledo"
   $colmena->usuario_id = 1
   $colmena->validar()
   $colmena->crear()
   ↓
5. MODELO ejecuta lógica de negocio
   - Valida datos
   - Ejecuta INSERT en BD
   - Retorna resultado
   ↓
6. CONTROLADOR formatea respuesta
   Crea array asociativo
   Añade código HTTP 201
   ↓
7. Respuesta JSON al cliente
   {
     "success": true,
     "data": { "id": 13, "zona": "Toledo" }
   }
```

## 💻 Análisis del Código - Línea por Línea

### Petición GET - Obtener todas las colmenas

#### Cliente hace petición
```bash
curl -X GET http://localhost/api/colmenas
```

#### 1. Enrutador (api.php)
```php
// Línea 20-22: Obtener método HTTP
$metodo = $_SERVER['REQUEST_METHOD']; // "GET"

// Línea 25-27: Obtener y limpiar URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// "/api-colmenas/public/api/colmenas"

// Línea 30: Separar en segmentos
$uri_segmentos = explode('/', $uri);
// [0]=>"", [1]=>"api-colmenas", [2]=>"public", [3]=>"api", [4]=>"colmenas"

// Línea 35: Buscar índice de 'api'
$api_index = array_search('api', $uri_segmentos); // 3

// Línea 43: Obtener recurso
$recurso = $uri_segmentos[$api_index + 1] ?? null; // "colmenas"

// Línea 46: Obtener ID (si existe)
$id = $uri_segmentos[$api_index + 2] ?? null; // null

// Línea 70: Instanciar controlador
$colmenaController = new ColmenaController($db);

// Línea 75-84: Switch según método
switch ($metodo) {
    case 'GET':
        if ($id) {
            // No hay ID
        } else {
            $colmenaController->obtenerTodas(); // ← Ejecuta esto
        }
        break;
}
```

#### 2. Controlador (ColmenaController.php)
```php
public function obtenerTodas() {
    // Línea 27: Llamar al modelo
    $stmt = $this->colmena->obtenerTodas();
    
    // Línea 28: Contar resultados
    $num = $stmt->rowCount();

    // Línea 30-50: Si hay resultados
    if ($num > 0) {
        $colmenas_arr = [];
        
        // Línea 33-45: Iterar sobre resultados
        while ($row = $stmt->fetch()) {
            // Crear array asociativo para cada colmena
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

        // Línea 47-51: Enviar respuesta exitosa
        $this->respuesta(200, [
            'success' => true,
            'total' => $num,
            'data' => $colmenas_arr
        ]);
    }
}
```

#### 3. Modelo (Colmena.php)
```php
public function obtenerTodas() {
    // Línea 35-43: Preparar query SQL
    $query = "SELECT 
                c.id,
                c.zona,
                c.usuario_id,
                c.fecha_creacion,
                u.username,
                u.nombre_completo as usuario_nombre
              FROM colmenas c
              LEFT JOIN usuarios u ON c.usuario_id = u.id
              ORDER BY c.id DESC";
    
    // Línea 45-46: Preparar y ejecutar
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    
    // Línea 48: Devolver statement
    return $stmt;
}
```

#### 4. Respuesta al Cliente
```php
private function respuesta($codigo, $datos) {
    // Línea 214: Establecer código HTTP
    http_response_code($codigo); // 200
    
    // Línea 215: Convertir a JSON y enviar
    echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
    // Línea 216: Terminar ejecución
    exit;
}
```

### Petición POST - Crear colmena

#### Cliente envía datos
```bash
curl -X POST http://localhost/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{"zona":"Toledo","usuario_id":1}'
```

#### Controlador recibe y procesa
```php
public function crear() {
    // Línea 100-101: Leer body de la petición
    $data = json_decode(file_get_contents("php://input"));
    // $data->zona = "Toledo"
    // $data->usuario_id = 1

    // Línea 104-109: Validar que llegaron datos
    if (empty($data)) {
        $this->respuesta(400, [
            'success' => false,
            'message' => 'No se recibieron datos'
        ]);
        return;
    }

    // Línea 113-114: Asignar al modelo
    $this->colmena->zona = $data->zona ?? '';
    $this->colmena->usuario_id = $data->usuario_id ?? '';

    // Línea 117: Validar datos
    $validacion = $this->colmena->validar();
    
    // Línea 119-126: Si hay errores de validación
    if (!$validacion['valido']) {
        $this->respuesta(400, [
            'success' => false,
            'message' => 'Error de validación',
            'errores' => $validacion['errores']
        ]);
        return;
    }

    // Línea 130: Intentar crear
    if ($this->colmena->crear()) {
        // Línea 131-138: Respuesta exitosa con código 201
        $this->respuesta(201, [
            'success' => true,
            'message' => 'Colmena creada correctamente',
            'data' => [
                'id' => (int) $this->colmena->id,
                'zona' => $this->colmena->zona,
                'usuario_id' => (int) $this->colmena->usuario_id
            ]
        ]);
    }
}
```

#### Modelo valida y guarda
```php
public function validar() {
    $errores = [];
    
    // Línea 148-150: Validar zona no vacía
    if (empty($this->zona)) {
        $errores[] = "El campo 'zona' es obligatorio";
    }
    
    // Línea 152-154: Validar longitud zona
    if (strlen($this->zona) > 100) {
        $errores[] = "El campo 'zona' no puede tener más de 100 caracteres";
    }
    
    // Línea 156-158: Validar usuario_id no vacío
    if (empty($this->usuario_id)) {
        $errores[] = "El campo 'usuario_id' es obligatorio";
    }
    
    // Línea 160-162: Validar que sea numérico
    if (!is_numeric($this->usuario_id) || $this->usuario_id <= 0) {
        $errores[] = "El campo 'usuario_id' debe ser un número positivo";
    }
    
    // Línea 164-167: Devolver resultado
    return [
        'valido' => empty($errores),
        'errores' => $errores
    ];
}

public function crear() {
    // Línea 95-97: Preparar query INSERT
    $query = "INSERT INTO colmenas
              SET zona = :zona,
                  usuario_id = :usuario_id";
    
    $stmt = $this->conn->prepare($query);
    
    // Línea 100-101: Limpiar datos
    $this->zona = htmlspecialchars(strip_tags($this->zona));
    $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
    
    // Línea 104-105: Bind de parámetros
    $stmt->bindParam(':zona', $this->zona);
    $stmt->bindParam(':usuario_id', $this->usuario_id);
    
    // Línea 107-111: Ejecutar y obtener ID
    if ($stmt->execute()) {
        $this->id = $this->conn->lastInsertId();
        return true;
    }
    
    return false;
}
```

## 🔒 Seguridad en APIs

### 1. SQL Injection - Prevención con PDO

#### ❌ VULNERABLE
```php
$query = "SELECT * FROM colmenas WHERE id = " . $_GET['id'];
$result = mysqli_query($conn, $query);
```

#### ✅ SEGURO
```php
$query = "SELECT * FROM colmenas WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
```

### 2. Sanitización de Datos

```php
// Remover etiquetas HTML y caracteres especiales
$this->zona = htmlspecialchars(strip_tags($this->zona));
```

### 3. Validación de Tipos

```php
// Asegurar que usuario_id es un entero
if (!is_numeric($this->usuario_id) || $this->usuario_id <= 0) {
    $errores[] = "usuario_id debe ser un número positivo";
}
```

## 📝 Ejercicios para Estudiantes

### Ejercicio 1: Añadir Filtros
Implementar filtrado por zona en GET:
```
GET /api/colmenas?zona=Toledo
```

**Pistas:**
- Modificar `obtenerTodas()` en el modelo
- Añadir parámetro `$filtros` al método
- Usar `$_GET['zona']` en el controlador

### Ejercicio 2: Paginación
Implementar paginación:
```
GET /api/colmenas?page=1&limit=10
```

**Respuesta esperada:**
```json
{
    "success": true,
    "total": 50,
    "page": 1,
    "limit": 10,
    "data": [ ... ]
}
```

### Ejercicio 3: Nuevo Recurso
Crear endpoint para usuarios:
```
GET    /api/usuarios
GET    /api/usuarios/{id}
POST   /api/usuarios
PUT    /api/usuarios/{id}
DELETE /api/usuarios/{id}
```

Archivos a crear:
- `models/Usuario.php`
- `controllers/UsuarioController.php`
- Modificar `public/api.php` para enrutar

### Ejercicio 4: Autenticación Básica
Implementar API Key simple:
```php
// Header requerido: X-API-Key: mi_clave_secreta

if ($_SERVER['HTTP_X_API_KEY'] !== 'mi_clave_secreta') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}
```

## 🎯 Conceptos Clave para Recordar

1. **REST usa HTTP de forma semántica**: GET para leer, POST para crear, etc.
2. **URLs son sustantivos**, métodos son verbos
3. **JSON es el formato estándar** para intercambio de datos
4. **Códigos HTTP comunican el resultado** de la operación
5. **MVC en APIs**: Modelo maneja datos, Controlador coordina, "Vista" es JSON
6. **Seguridad**: Siempre usar prepared statements y validar datos
7. **Idempotencia**: GET, PUT, DELETE son idempotentes; POST no
8. **Sin estado**: Cada petición es independiente

## 📚 Recursos Adicionales

- RFC 2616: Especificación HTTP/1.1
- JSON Schema: Para validación de JSON
- Postman: Herramienta para probar APIs
- cURL: Cliente HTTP por línea de comandos
- Swagger/OpenAPI: Documentación de APIs

---

**¡Domina las APIs REST y abre las puertas al desarrollo moderno! 🚀**
