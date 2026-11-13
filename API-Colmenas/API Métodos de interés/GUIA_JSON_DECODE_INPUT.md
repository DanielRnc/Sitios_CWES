# 📚 GUÍA DIDÁCTICA: json_decode(file_get_contents("php://input"))

## 🎯 ¿Qué hace `json_decode(file_get_contents("php://input"))`?

Esta línea de código es **crucial** para recibir datos JSON en APIs REST. Vamos a desglosarla paso por paso desde dentro hacia fuera.

---

## 📖 Desglose Completo (de Dentro hacia Fuera)

### 1. `"php://input"` - El Stream de Entrada

`php://input` es un **stream de solo lectura** que permite acceder al **cuerpo (body) crudo** de la petición HTTP.

#### ¿Qué es un Stream?

Un stream es un **flujo de datos** que se puede leer o escribir. En PHP, los streams se representan como URLs especiales.

#### Streams comunes en PHP:

| Stream | Descripción | Ejemplo de Uso |
|--------|-------------|----------------|
| `php://input` | Cuerpo crudo de la petición | Leer JSON de POST/PUT |
| `php://output` | Buffer de salida | Escribir respuesta |
| `php://stdin` | Entrada estándar | Scripts de consola |
| `php://stdout` | Salida estándar | Scripts de consola |
| `php://memory` | Memoria temporal | Operaciones en RAM |

#### ¿Qué contiene `php://input`?

```
Cliente envía:
POST /api/colmenas HTTP/1.1
Content-Type: application/json

{"zona": "Toledo", "usuario_id": 1}
         ↑
         Este contenido es lo que lee php://input
```

**Importante:**
- ✅ Contiene el **body completo** de la petición
- ✅ Funciona con POST, PUT, PATCH, DELETE
- ❌ NO funciona con GET (GET no tiene body)
- ❌ NO funciona con `enctype="multipart/form-data"` (archivos)

---

### 2. `file_get_contents()` - Leer el Stream

`file_get_contents()` es una función que **lee el contenido completo** de un archivo o stream y lo retorna como un string.

#### Sintaxis:
```php
file_get_contents(string $filename): string|false
```

#### Usos comunes:

```php
<?php
// Leer archivo del disco
$contenido = file_get_contents('archivo.txt');

// Leer URL remota
$html = file_get_contents('https://ejemplo.com');

// Leer stream de entrada HTTP ← ¡Nuestro caso!
$body = file_get_contents('php://input');
?>
```

#### En el contexto de APIs:

```php
<?php
// Cliente envía JSON en el body
$json_string = file_get_contents('php://input');
// Resultado: '{"zona": "Toledo", "usuario_id": 1}'
// Tipo: string

echo gettype($json_string); // "string"
echo $json_string; // {"zona": "Toledo", "usuario_id": 1}
?>
```

---

### 3. `json_decode()` - Convertir JSON a Objeto PHP

`json_decode()` toma un **string JSON** y lo convierte en una **estructura de datos PHP** (objeto o array).

#### Sintaxis:
```php
json_decode(
    string $json,
    ?bool $associative = null,
    int $depth = 512,
    int $flags = 0
): mixed
```

#### Parámetros importantes:

| Parámetro | Valor | Resultado |
|-----------|-------|-----------|
| `$associative = null` o `false` | (default) | Objeto stdClass |
| `$associative = true` | true | Array asociativo |

#### Ejemplos:

```php
<?php
$json = '{"zona": "Toledo", "usuario_id": 1}';

// Como objeto (default)
$objeto = json_decode($json);
echo $objeto->zona;       // "Toledo"
echo $objeto->usuario_id; // 1
echo gettype($objeto);    // "object"

// Como array asociativo
$array = json_decode($json, true);
echo $array['zona'];       // "Toledo"
echo $array['usuario_id']; // 1
echo gettype($array);      // "array"
?>
```

---

## 🔍 La Línea Completa Explicada

```php
<?php
$data = json_decode(file_get_contents("php://input"));

// Paso 1: file_get_contents("php://input")
//         Lee el body HTTP como string
//         Resultado: '{"zona": "Toledo", "usuario_id": 1}'

// Paso 2: json_decode(...)
//         Convierte el string JSON a objeto PHP
//         Resultado: stdClass Object con propiedades zona y usuario_id

// Paso 3: Asignar a $data
//         Ahora podemos acceder a los datos:
//         $data->zona = "Toledo"
//         $data->usuario_id = 1
?>
```

---

## 📡 Flujo Completo en una API REST

### Ejemplo Real: POST /api/colmenas

#### 1. Cliente Envía Petición

```javascript
// Cliente (JavaScript)
fetch('http://localhost/api/colmenas', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        zona: 'Toledo',
        usuario_id: 1
    })
});
```

#### 2. Petición HTTP Real

```http
POST /api/colmenas HTTP/1.1
Host: localhost
Content-Type: application/json
Content-Length: 41

{"zona":"Toledo","usuario_id":1}
```

**Desglose:**
- **Headers**: `Content-Type: application/json` indica que el body es JSON
- **Body**: `{"zona":"Toledo","usuario_id":1}` es el contenido que viaja

#### 3. Servidor PHP Recibe

```php
<?php
// En este punto:
// $_POST está VACÍO porque el Content-Type es application/json
// (PHP solo llena $_POST con application/x-www-form-urlencoded)

var_dump($_POST); // array(0) { } ← ¡Vacío!

// Por eso necesitamos leer php://input manualmente
$json_string = file_get_contents('php://input');
// $json_string = '{"zona":"Toledo","usuario_id":1}'

$data = json_decode($json_string);
// $data = stdClass Object
// (
//     [zona] => Toledo
//     [usuario_id] => 1
// )

// Ahora podemos acceder a los datos
echo $data->zona;       // "Toledo"
echo $data->usuario_id; // 1
?>
```

---

## 🆚 Comparación: $_POST vs php://input

### ❌ Por qué $_POST no funciona con JSON

```php
<?php
/**
 * Cliente envía:
 * Content-Type: application/json
 * Body: {"zona": "Toledo", "usuario_id": 1}
 */

// Intento 1: Usar $_POST (NO FUNCIONA)
$zona = $_POST['zona'] ?? 'no existe';
// Resultado: "no existe"
// ¿Por qué? Porque $_POST solo funciona con:
//   - application/x-www-form-urlencoded
//   - multipart/form-data

var_dump($_POST); // array(0) { } ← ¡Vacío!

// Intento 2: Usar php://input (FUNCIONA)
$data = json_decode(file_get_contents('php://input'));
$zona = $data->zona;
// Resultado: "Toledo" ← ¡Correcto!
?>
```

### ✅ Cuándo usar cada método

| Método | Cuándo Usar | Content-Type |
|--------|-------------|--------------|
| `$_POST` | Formularios HTML tradicionales | `application/x-www-form-urlencoded` |
| `$_POST` | Subida de archivos | `multipart/form-data` |
| `php://input` + `json_decode()` | **APIs REST con JSON** | `application/json` |
| `php://input` + `parse_str()` | URL encoded sin `$_POST` | `application/x-www-form-urlencoded` |

---

## 💻 Ejemplo Completo en Controlador

### Código del Controlador

```php
<?php
/**
 * Método crear() del ColmenaController
 * Recibe JSON y crea una colmena
 */

public function crear() {
    // PASO 1: Obtener el body de la petición
    $raw_input = file_get_contents('php://input');
    
    // En este punto, $raw_input es un string:
    // '{"zona":"Toledo","usuario_id":1}'
    
    // PASO 2: Validar que se recibió algo
    if (empty($raw_input)) {
        $this->respuesta(400, [
            'success' => false,
            'message' => 'No se recibieron datos'
        ]);
        return;
    }
    
    // PASO 3: Decodificar JSON a objeto PHP
    $data = json_decode($raw_input);
    
    // PASO 4: Validar que el JSON es válido
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        $this->respuesta(400, [
            'success' => false,
            'message' => 'JSON inválido: ' . json_last_error_msg()
        ]);
        return;
    }
    
    // PASO 5: Validar que tiene los campos necesarios
    if (empty($data)) {
        $this->respuesta(400, [
            'success' => false,
            'message' => 'Datos vacíos o formato JSON inválido'
        ]);
        return;
    }
    
    // PASO 6: Acceder a los datos (usar ?? para valores por defecto)
    $this->colmena->zona = $data->zona ?? '';
    $this->colmena->usuario_id = $data->usuario_id ?? '';
    
    // PASO 7: Validar datos de negocio
    $validacion = $this->colmena->validar();
    
    if (!$validacion['valido']) {
        $this->respuesta(400, [
            'success' => false,
            'message' => 'Error de validación',
            'errores' => $validacion['errores']
        ]);
        return;
    }
    
    // PASO 8: Crear colmena
    if ($this->colmena->crear()) {
        $this->respuesta(201, [
            'success' => true,
            'message' => 'Colmena creada correctamente',
            'data' => [
                'id' => $this->colmena->id,
                'zona' => $this->colmena->zona,
                'usuario_id' => $this->colmena->usuario_id
            ]
        ]);
    } else {
        $this->respuesta(500, [
            'success' => false,
            'message' => 'Error al crear la colmena'
        ]);
    }
}
?>
```

---

## 🔬 Ejemplos Prácticos Detallados

### Ejemplo 1: POST Simple

#### Cliente envía:
```bash
curl -X POST http://localhost/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{"zona":"Toledo","usuario_id":1}'
```

#### Servidor recibe:
```php
<?php
$raw = file_get_contents('php://input');
// '{"zona":"Toledo","usuario_id":1}'

$data = json_decode($raw);
// stdClass Object ( [zona] => Toledo [usuario_id] => 1 )

echo $data->zona;       // "Toledo"
echo $data->usuario_id; // 1
?>
```

### Ejemplo 2: POST con Datos Complejos

#### Cliente envía:
```bash
curl -X POST http://localhost/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{
    "zona": "Toledo",
    "usuario_id": 1,
    "sensores": [
      {"tipo": "temperatura", "valor": 25.5},
      {"tipo": "humedad", "valor": 60}
    ],
    "activa": true
  }'
```

#### Servidor recibe:
```php
<?php
$data = json_decode(file_get_contents('php://input'));

echo $data->zona;                    // "Toledo"
echo $data->usuario_id;              // 1
echo $data->sensores[0]->tipo;       // "temperatura"
echo $data->sensores[0]->valor;      // 25.5
echo $data->sensores[1]->tipo;       // "humedad"
echo $data->sensores[1]->valor;      // 60
var_dump($data->activa);             // bool(true)
?>
```

### Ejemplo 3: POST con Array Asociativo

#### Cliente envía:
```bash
curl -X POST http://localhost/api/colmenas/batch \
  -H "Content-Type: application/json" \
  -d '[
    {"zona":"Toledo","usuario_id":1},
    {"zona":"Madrid","usuario_id":2},
    {"zona":"Barcelona","usuario_id":3}
  ]'
```

#### Servidor recibe:
```php
<?php
// Usar segundo parámetro true para array asociativo
$data = json_decode(file_get_contents('php://input'), true);

// $data es ahora un array
foreach ($data as $colmena) {
    echo $colmena['zona'] . "\n";
    echo $colmena['usuario_id'] . "\n";
}

// Salida:
// Toledo
// 1
// Madrid
// 2
// Barcelona
// 3
?>
```

---

## ⚠️ Manejo de Errores

### Error 1: JSON Inválido

```php
<?php
// Cliente envía JSON malformado:
// {zona: "Toledo", usuario_id: 1}  ← Sin comillas en las claves

$raw = file_get_contents('php://input');
// '{zona: "Toledo", usuario_id: 1}'

$data = json_decode($raw);
// $data = null (porque el JSON es inválido)

// Verificar si hubo error
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    echo "Error: " . json_last_error_msg();
    // Salida: "Error: Syntax error"
    
    // Enviar respuesta de error al cliente
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'JSON inválido',
        'error' => json_last_error_msg()
    ]);
    exit;
}
?>
```

### Error 2: Body Vacío

```php
<?php
// Cliente hace POST pero no envía body

$raw = file_get_contents('php://input');
// $raw = '' (string vacío)

$data = json_decode($raw);
// $data = null

// Verificar si está vacío ANTES de decodificar
if (empty($raw)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'No se recibieron datos'
    ]);
    exit;
}
?>
```

### Error 3: Content-Type Incorrecto

```php
<?php
// Cliente envía con Content-Type incorrecto
// Content-Type: text/plain
// Body: {"zona":"Toledo"}

// El código funcionará, pero es mala práctica
// Mejor: Validar el Content-Type

$content_type = $_SERVER['CONTENT_TYPE'] ?? '';

if (strpos($content_type, 'application/json') === false) {
    http_response_code(415); // Unsupported Media Type
    echo json_encode([
        'success' => false,
        'message' => 'Content-Type debe ser application/json'
    ]);
    exit;
}
?>
```

---

## 🎯 Función Completa de Validación

```php
<?php
/**
 * Función helper para obtener y validar JSON del body
 * 
 * @return object|null Objeto decodificado o null si hay error
 */
function obtenerJsonDelBody() {
    // 1. Validar Content-Type
    $content_type = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($content_type, 'application/json') === false) {
        http_response_code(415);
        echo json_encode([
            'success' => false,
            'message' => 'Content-Type debe ser application/json'
        ]);
        return null;
    }
    
    // 2. Leer body
    $raw = file_get_contents('php://input');
    
    // 3. Validar que no esté vacío
    if (empty($raw)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Body vacío'
        ]);
        return null;
    }
    
    // 4. Decodificar JSON
    $data = json_decode($raw);
    
    // 5. Validar JSON válido
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'JSON inválido',
            'error' => json_last_error_msg()
        ]);
        return null;
    }
    
    // 6. Validar que tenga contenido
    if (empty($data)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Datos vacíos'
        ]);
        return null;
    }
    
    return $data;
}

// Uso:
$data = obtenerJsonDelBody();
if ($data === null) {
    exit; // Ya se envió respuesta de error
}

// Continuar con la lógica
echo $data->zona;
?>
```

---

## 🔄 Comparación con otros Métodos

### Método 1: file_get_contents() + json_decode() (Recomendado)

```php
<?php
$data = json_decode(file_get_contents('php://input'));
// ✅ Simple y directo
// ✅ Lee todo el body de una vez
// ✅ Funciona con POST, PUT, PATCH, DELETE
?>
```

### Método 2: fopen() + fread() (Más control)

```php
<?php
$handle = fopen('php://input', 'r');
$raw = '';
while (!feof($handle)) {
    $raw .= fread($handle, 1024); // Leer en chunks de 1KB
}
fclose($handle);
$data = json_decode($raw);
// ✅ Permite leer en chunks (útil para datos grandes)
// ❌ Más código
?>
```

### Método 3: stream_get_contents() (Alternativa)

```php
<?php
$handle = fopen('php://input', 'r');
$raw = stream_get_contents($handle);
fclose($handle);
$data = json_decode($raw);
// ✅ Similar a file_get_contents()
// ❌ Requiere abrir/cerrar el handle
?>
```

### Método 4: $HTTP_RAW_POST_DATA (Obsoleto)

```php
<?php
// ❌ DEPRECATED desde PHP 5.6
// ❌ ELIMINADO en PHP 7.0
$data = json_decode($HTTP_RAW_POST_DATA);
// ❌ NO USAR
?>
```

---

## 📊 Diagrama de Flujo Completo

```
Cliente hace POST
    ↓
Petición HTTP con JSON en body
    ↓
{"zona": "Toledo", "usuario_id": 1}
    ↓
Servidor PHP recibe petición
    ↓
file_get_contents("php://input")
    ↓
'{"zona": "Toledo", "usuario_id": 1}' (string)
    ↓
json_decode()
    ↓
stdClass Object (
    [zona] => Toledo
    [usuario_id] => 1
)
    ↓
Acceso a propiedades
    ↓
$data->zona = "Toledo"
$data->usuario_id = 1
    ↓
Validación y procesamiento
    ↓
Respuesta JSON al cliente
```

---

## 🧪 Ejercicios Prácticos

### Ejercicio 1: Identificar el Problema

```php
<?php
// Cliente envía POST con JSON:
// {"zona": "Toledo", "usuario_id": 1}

$zona = $_POST['zona'];
$usuario_id = $_POST['usuario_id'];

// ¿Por qué no funciona?
// ¿Cómo lo arreglarías?
?>
```

**Respuesta:**
```php
<?php
// Problema: $_POST no funciona con application/json
// Solución:
$data = json_decode(file_get_contents('php://input'));
$zona = $data->zona;
$usuario_id = $data->usuario_id;
?>
```

### Ejercicio 2: Manejar Diferentes Formatos

```php
<?php
// Cliente puede enviar:
// 1. Objeto: {"zona": "Toledo"}
// 2. Array: [{"zona": "Toledo"}, {"zona": "Madrid"}]

$raw = file_get_contents('php://input');
$data = json_decode($raw);

// ¿Cómo determinar si es objeto o array?
// ¿Cómo procesarlos correctamente?
?>
```

**Respuesta:**
```php
<?php
$data = json_decode(file_get_contents('php://input'));

if (is_array($data)) {
    // Es un array
    foreach ($data as $item) {
        echo $item->zona . "\n";
    }
} else if (is_object($data)) {
    // Es un objeto
    echo $data->zona . "\n";
}
?>
```

### Ejercicio 3: Crear Función Robusta

```php
<?php
// Crea una función que:
// 1. Lea php://input
// 2. Valide que no esté vacío
// 3. Decodifique JSON
// 4. Maneje errores apropiadamente
// 5. Retorne el objeto o false
?>
```

**Respuesta:**
```php
<?php
function obtenerJson() {
    $raw = file_get_contents('php://input');
    
    if (empty($raw)) {
        return false;
    }
    
    $data = json_decode($raw);
    
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }
    
    return $data;
}

// Uso:
$data = obtenerJson();
if ($data === false) {
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}
?>
```

---

## 💡 Buenas Prácticas

### 1. Siempre Validar el JSON

```php
<?php
$data = json_decode(file_get_contents('php://input'));

// ✅ BIEN: Validar que decodificó correctamente
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    // Manejar error
}

// ❌ MAL: Asumir que siempre es válido
$zona = $data->zona; // ¡Puede causar error si $data es null!
?>
```

### 2. Usar Valores por Defecto

```php
<?php
$data = json_decode(file_get_contents('php://input'));

// ✅ BIEN: Usar operador null coalescing
$zona = $data->zona ?? '';
$usuario_id = $data->usuario_id ?? 0;

// ❌ MAL: Acceso directo sin validar
$zona = $data->zona; // Error si 'zona' no existe
?>
```

### 3. Validar Content-Type

```php
<?php
// ✅ BIEN: Validar que es JSON
$content_type = $_SERVER['CONTENT_TYPE'] ?? '';
if (strpos($content_type, 'application/json') === false) {
    // Error 415: Unsupported Media Type
    http_response_code(415);
    exit;
}

$data = json_decode(file_get_contents('php://input'));
?>
```

### 4. Loguear Errores

```php
<?php
$raw = file_get_contents('php://input');
$data = json_decode($raw);

if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    // ✅ BIEN: Loguear para debugging
    error_log("JSON Error: " . json_last_error_msg());
    error_log("Raw input: " . $raw);
    
    // Responder al cliente
    http_response_code(400);
    echo json_encode(['error' => 'JSON inválido']);
    exit;
}
?>
```

---

## 📝 Resumen

### ¿Qué hace `json_decode(file_get_contents("php://input"))`?

1. **`php://input`**: Stream que contiene el body crudo de la petición HTTP
2. **`file_get_contents()`**: Lee el stream completo como string
3. **`json_decode()`**: Convierte el string JSON a objeto/array PHP

### ¿Por qué es necesario en APIs REST?

- ✅ $_POST no funciona con `Content-Type: application/json`
- ✅ Permite recibir JSON en POST, PUT, PATCH, DELETE
- ✅ Es el estándar para APIs REST modernas
- ✅ Permite estructuras de datos complejas

### Flujo típico:

```
Cliente → JSON string → HTTP Body → php://input → 
file_get_contents() → String → json_decode() → 
Objeto PHP → Procesamiento
```

### Cuándo usar:

- ✅ APIs REST con JSON
- ✅ POST/PUT/PATCH/DELETE con datos JSON
- ✅ Cuando `Content-Type: application/json`

### Cuándo NO usar:

- ❌ Formularios HTML normales (usar $_POST)
- ❌ Subida de archivos (usar $_FILES)
- ❌ Peticiones GET (no tienen body)

---

**¡Ahora dominas completamente cómo recibir JSON en APIs REST con PHP! 🚀**
