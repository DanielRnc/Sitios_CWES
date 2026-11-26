# 📚 GUÍA DIDÁCTICA: parse_url() en APIs RESTful

## 🎯 ¿Qué hace `parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)`?

Esta línea de código es **fundamental** para el enrutamiento en APIs REST. Vamos a desglosarla paso a paso.

---

## 📖 Desglose Completo

### 1. `$_SERVER['REQUEST_URI']`

`$_SERVER` es una **variable superglobal de PHP** que contiene información sobre el servidor y la petición HTTP actual.

`$_SERVER['REQUEST_URI']` contiene la **URI completa** que el cliente solicitó.

#### Ejemplo:
```
URL completa del navegador:
http://localhost/api-colmenas/public/api/colmenas/1?zona=Toledo&page=2

$_SERVER['REQUEST_URI'] contendrá:
/api-colmenas/public/api/colmenas/1?zona=Toledo&page=2
```

**Importante:** `REQUEST_URI` incluye:
- ✅ La ruta (path)
- ✅ Los parámetros de consulta (query string)
- ❌ NO incluye el dominio
- ❌ NO incluye el protocolo (http/https)

---

### 2. `parse_url()`

Es una función nativa de PHP que **analiza una URL** y devuelve sus componentes como un array asociativo.

#### Sintaxis:
```php
parse_url(string $url, int $component = -1): mixed
```

#### Componentes que puede extraer:

| Componente | Constante PHP | Ejemplo |
|------------|---------------|---------|
| Esquema | `PHP_URL_SCHEME` | `http`, `https` |
| Host | `PHP_URL_HOST` | `localhost` |
| Puerto | `PHP_URL_PORT` | `8080` |
| Usuario | `PHP_URL_USER` | `usuario` |
| Contraseña | `PHP_URL_PASS` | `password` |
| **Ruta** | `PHP_URL_PATH` | `/api/colmenas/1` |
| Query String | `PHP_URL_QUERY` | `zona=Toledo&page=2` |
| Fragmento | `PHP_URL_FRAGMENT` | `#seccion` |

---

### 3. `PHP_URL_PATH`

Al especificar `PHP_URL_PATH` como segundo parámetro, le decimos a `parse_url()` que **solo queremos la ruta**, sin los parámetros de consulta.

#### Ejemplo Completo:

```php
<?php
// Supongamos que el cliente hace esta petición:
// GET http://localhost/api-colmenas/public/api/colmenas/1?zona=Toledo&page=2

// REQUEST_URI contiene TODO después del dominio
$request_uri = $_SERVER['REQUEST_URI'];
// Valor: "/api-colmenas/public/api/colmenas/1?zona=Toledo&page=2"

// parse_url() con PHP_URL_PATH extrae SOLO la ruta
$path = parse_url($request_uri, PHP_URL_PATH);
// Valor: "/api-colmenas/public/api/colmenas/1"

// Los parámetros (?zona=Toledo&page=2) se quedan fuera
echo $path;
// Resultado: /api-colmenas/public/api/colmenas/1
?>
```

---

## 🔍 Comparación: Con y Sin parse_url()

### ❌ SIN parse_url() - Problema

```php
<?php
// Cliente solicita: /api/colmenas?zona=Toledo&page=2

$uri = $_SERVER['REQUEST_URI'];
// Valor: "/api/colmenas?zona=Toledo&page=2"

$uri_segmentos = explode('/', $uri);
// Array: ["", "api", "colmenas?zona=Toledo&page=2"]
//                                  ↑
//                    ¡Los parámetros contaminan el último segmento!

$recurso = $uri_segmentos[2];
// Valor: "colmenas?zona=Toledo&page=2" ← ¡ERROR!
// Esperábamos: "colmenas"
?>
```

### ✅ CON parse_url() - Solución

```php
<?php
// Cliente solicita: /api/colmenas?zona=Toledo&page=2

$uri = $_SERVER['REQUEST_URI'];
// Valor: "/api/colmenas?zona=Toledo&page=2"

// Limpiar primero con parse_url
$uri = parse_url($uri, PHP_URL_PATH);
// Valor: "/api/colmenas" ← ¡Sin parámetros!

$uri_segmentos = explode('/', $uri);
// Array: ["", "api", "colmenas"]
//                         ↑
//                    ¡Limpio!

$recurso = $uri_segmentos[2];
// Valor: "colmenas" ← ¡CORRECTO!
?>
```

---

## 🚀 Uso en el Enrutador de una API REST

### Contexto Completo

```php
<?php
/**
 * ENRUTADOR DE API REST
 * Ejemplo real de cómo se usa parse_url() en nuestra API de colmenas
 */

// Cliente solicita:
// GET http://localhost/api-colmenas/public/api/colmenas/5?zona=Toledo&page=2

// PASO 1: Obtener la URI completa
$request_uri = $_SERVER['REQUEST_URI'];
// "/api-colmenas/public/api/colmenas/5?zona=Toledo&page=2"

// PASO 2: Extraer SOLO la ruta (sin query string)
$path = parse_url($request_uri, PHP_URL_PATH);
// "/api-colmenas/public/api/colmenas/5"

// PASO 3: Limpiar barras finales
$path = rtrim($path, '/');
// "/api-colmenas/public/api/colmenas/5"

// PASO 4: Dividir en segmentos
$segmentos = explode('/', $path);
// ["", "api-colmenas", "public", "api", "colmenas", "5"]
//   0        1            2        3        4        5

// PASO 5: Encontrar dónde está 'api'
$api_index = array_search('api', $segmentos);
// 3

// PASO 6: Extraer el recurso (después de 'api')
$recurso = $segmentos[$api_index + 1] ?? null;
// "colmenas"

// PASO 7: Extraer el ID (si existe)
$id = $segmentos[$api_index + 2] ?? null;
// "5"

// PASO 8: Enrutar según el recurso
if ($recurso === 'colmenas') {
    if ($id) {
        // GET /api/colmenas/5
        obtenerColmenaPorId($id);
    } else {
        // GET /api/colmenas
        // Los parámetros están disponibles en $_GET
        $zona = $_GET['zona'] ?? null;  // "Toledo"
        $page = $_GET['page'] ?? 1;     // 2
        obtenerTodasLasColmenas($zona, $page);
    }
}
?>
```

---

## 💡 ¿Por qué es tan importante en APIs REST?

### 1. **Separación de Responsabilidades**

En REST, hay dos formas de pasar información:

| Tipo | Ubicación | Uso | Ejemplo |
|------|-----------|-----|---------|
| **Ruta (Path)** | En la URL | Identificar **recursos** | `/api/colmenas/5` |
| **Query String** | Después de `?` | Filtrar, paginar, ordenar | `?zona=Toledo&page=2` |

`parse_url()` nos permite **separarlos limpiamente**.

### 2. **Enrutamiento RESTful**

Las APIs REST usan la **ruta** para determinar qué hacer:

```php
<?php
// Todas estas URLs deben enrutarse correctamente:

// 1. /api/colmenas
//    → Listar todas las colmenas

// 2. /api/colmenas/5
//    → Obtener colmena con ID 5

// 3. /api/colmenas?zona=Toledo
//    → Listar colmenas, filtrar por zona
//    La ruta es: /api/colmenas
//    El filtro es: ?zona=Toledo (en $_GET)

// 4. /api/colmenas/5?incluir=sensores
//    → Obtener colmena 5 con sensores
//    La ruta es: /api/colmenas/5
//    La opción es: ?incluir=sensores (en $_GET)

// parse_url() asegura que extraemos SOLO la ruta
// para el enrutamiento
?>
```

### 3. **Evitar Errores de Parsing**

Sin `parse_url()`, el query string puede causar problemas:

```php
<?php
// ❌ PROBLEMA sin parse_url()
$uri = "/api/colmenas?zona=Toledo&page=2";
$partes = explode('/', $uri);
// ["", "api", "colmenas?zona=Toledo&page=2"]
//                       ↑
//              ¡Contiene parámetros!

$recurso = $partes[2];
if ($recurso === 'colmenas') {
    // ¡Nunca se cumple! Porque $recurso es "colmenas?zona=Toledo&page=2"
}

// ✅ SOLUCIÓN con parse_url()
$uri = parse_url("/api/colmenas?zona=Toledo&page=2", PHP_URL_PATH);
// "/api/colmenas"
$partes = explode('/', $uri);
// ["", "api", "colmenas"]
$recurso = $partes[2]; // "colmenas" ← ¡Limpio!
?>
```

---

## 📊 Diagrama de Flujo

```
Petición HTTP del cliente
         |
         v
GET /api-colmenas/public/api/colmenas/5?zona=Toledo&page=2
         |
         v
$_SERVER['REQUEST_URI']
"/api-colmenas/public/api/colmenas/5?zona=Toledo&page=2"
         |
         v
parse_url($uri, PHP_URL_PATH)
         |
         v
"/api-colmenas/public/api/colmenas/5"  ← Solo la ruta
         |
         v
explode('/', $path)
         |
         v
["", "api-colmenas", "public", "api", "colmenas", "5"]
         |
         v
array_search('api', $segmentos)
         |
         v
$recurso = "colmenas"
$id = "5"
         |
         v
Enrutador decide qué hacer
         |
         v
Respuesta JSON
```

---

## 🔬 Ejemplos Prácticos Paso a Paso

### Ejemplo 1: Petición Simple

```php
<?php
// Cliente solicita: GET /api/colmenas

$uri = $_SERVER['REQUEST_URI'];
// "/api/colmenas"

$path = parse_url($uri, PHP_URL_PATH);
// "/api/colmenas"

$segmentos = explode('/', trim($path, '/'));
// ["api", "colmenas"]

$recurso = $segmentos[1];
// "colmenas"

echo "Recurso: $recurso\n";
// Salida: Recurso: colmenas
?>
```

### Ejemplo 2: Petición con ID

```php
<?php
// Cliente solicita: GET /api/colmenas/42

$uri = $_SERVER['REQUEST_URI'];
// "/api/colmenas/42"

$path = parse_url($uri, PHP_URL_PATH);
// "/api/colmenas/42"

$segmentos = explode('/', trim($path, '/'));
// ["api", "colmenas", "42"]

$recurso = $segmentos[1];
// "colmenas"

$id = $segmentos[2] ?? null;
// "42"

echo "Recurso: $recurso, ID: $id\n";
// Salida: Recurso: colmenas, ID: 42
?>
```

### Ejemplo 3: Petición con Parámetros

```php
<?php
// Cliente solicita: GET /api/colmenas?zona=Toledo&page=2&limit=10

$uri = $_SERVER['REQUEST_URI'];
// "/api/colmenas?zona=Toledo&page=2&limit=10"

// Sin parse_url - ¡ERROR!
$segmentos_mal = explode('/', $uri);
// ["", "api", "colmenas?zona=Toledo&page=2&limit=10"]
echo "Sin parse_url: " . $segmentos_mal[2] . "\n";
// Salida: colmenas?zona=Toledo&page=2&limit=10 ← ¡MAL!

// Con parse_url - ¡CORRECTO!
$path = parse_url($uri, PHP_URL_PATH);
// "/api/colmenas"

$segmentos = explode('/', trim($path, '/'));
// ["api", "colmenas"]

echo "Con parse_url: " . $segmentos[1] . "\n";
// Salida: colmenas ← ¡BIEN!

// Los parámetros están en $_GET
$zona = $_GET['zona'];   // "Toledo"
$page = $_GET['page'];   // "2"
$limit = $_GET['limit']; // "10"
?>
```

### Ejemplo 4: Petición con Subrutas

```php
<?php
// Cliente solicita: GET /api/colmenas/stats/zonas

$uri = $_SERVER['REQUEST_URI'];
// "/api/colmenas/stats/zonas"

$path = parse_url($uri, PHP_URL_PATH);
// "/api/colmenas/stats/zonas"

$segmentos = explode('/', trim($path, '/'));
// ["api", "colmenas", "stats", "zonas"]

$recurso = $segmentos[1];      // "colmenas"
$subruta1 = $segmentos[2] ?? null; // "stats"
$subruta2 = $segmentos[3] ?? null; // "zonas"

if ($recurso === 'colmenas' && $subruta1 === 'stats' && $subruta2 === 'zonas') {
    echo "Ejecutar: obtenerEstadisticasPorZona()\n";
}
?>
```

---

## 🎓 Casos de Uso en APIs REST

### Caso 1: CRUD Básico

```php
<?php
$metodo = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segmentos = explode('/', trim($path, '/'));

$recurso = $segmentos[1] ?? null; // "colmenas"
$id = $segmentos[2] ?? null;

switch ($metodo) {
    case 'GET':
        if ($id) {
            // GET /api/colmenas/5
            obtenerColmena($id);
        } else {
            // GET /api/colmenas
            listarColmenas();
        }
        break;
    
    case 'POST':
        // POST /api/colmenas
        crearColmena();
        break;
    
    case 'PUT':
        // PUT /api/colmenas/5
        actualizarColmena($id);
        break;
    
    case 'DELETE':
        // DELETE /api/colmenas/5
        eliminarColmena($id);
        break;
}
?>
```

### Caso 2: Recursos Anidados

```php
<?php
// GET /api/usuarios/3/colmenas/7
// "Dame la colmena 7 del usuario 3"

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segmentos = explode('/', trim($path, '/'));
// ["api", "usuarios", "3", "colmenas", "7"]

$recurso1 = $segmentos[1]; // "usuarios"
$id1 = $segmentos[2];      // "3"
$recurso2 = $segmentos[3]; // "colmenas"
$id2 = $segmentos[4];      // "7"

if ($recurso1 === 'usuarios' && $recurso2 === 'colmenas') {
    obtenerColmenaDeUsuario($id1, $id2);
}
?>
```

### Caso 3: Acciones Especiales

```php
<?php
// POST /api/colmenas/5/transferir
// "Transferir la colmena 5 a otro usuario"

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segmentos = explode('/', trim($path, '/'));
// ["api", "colmenas", "5", "transferir"]

$recurso = $segmentos[1]; // "colmenas"
$id = $segmentos[2];      // "5"
$accion = $segmentos[3];  // "transferir"

if ($recurso === 'colmenas' && $accion === 'transferir') {
    transferirColmena($id);
}
?>
```

---

## ⚠️ Errores Comunes y Soluciones

### Error 1: No usar parse_url()

```php
<?php
// ❌ MALO
$uri = $_SERVER['REQUEST_URI']; // "/api/colmenas?zona=Toledo"
$partes = explode('/', $uri);
// ["", "api", "colmenas?zona=Toledo"] ← ¡Contiene parámetros!

// ✅ BUENO
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$partes = explode('/', $uri);
// ["", "api", "colmenas"] ← ¡Limpio!
?>
```

### Error 2: No validar segmentos vacíos

```php
<?php
// ❌ MALO
$segmentos = explode('/', "/api/colmenas/");
// ["", "api", "colmenas", ""] ← ¡Último elemento vacío!

// ✅ BUENO
$path = rtrim($path, '/'); // Quitar barra final
$segmentos = explode('/', $path);
// ["", "api", "colmenas"] ← ¡Sin elemento vacío!
?>
```

### Error 3: No manejar índices inexistentes

```php
<?php
// ❌ MALO
$id = $segmentos[5]; // ¡Puede no existir!

// ✅ BUENO
$id = $segmentos[5] ?? null; // Usa null si no existe
// O
$id = isset($segmentos[5]) ? $segmentos[5] : null;
?>
```

---

## 🧪 Ejercicio Práctico

### Ejercicio 1: Analizar URLs

Dado el siguiente código, indica qué valores tendrán las variables:

```php
<?php
// Cliente solicita: GET /api/usuarios/10/colmenas/5?incluir=sensores&page=2

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$segmentos = explode('/', trim($path, '/'));

$recurso1 = $segmentos[1];
$id1 = $segmentos[2];
$recurso2 = $segmentos[3];
$id2 = $segmentos[4];

// ¿Qué valores tienen?
// $uri = ?
// $path = ?
// $segmentos = ?
// $recurso1 = ?
// $id1 = ?
// $recurso2 = ?
// $id2 = ?
?>
```

**Respuestas:**
```
$uri = "/api/usuarios/10/colmenas/5?incluir=sensores&page=2"
$path = "/api/usuarios/10/colmenas/5"
$segmentos = ["api", "usuarios", "10", "colmenas", "5"]
$recurso1 = "usuarios"
$id1 = "10"
$recurso2 = "colmenas"
$id2 = "5"
```

### Ejercicio 2: Crear un Enrutador

Completa el siguiente enrutador para que funcione correctamente:

```php
<?php
$metodo = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segmentos = explode('/', trim($path, '/'));

$recurso = $segmentos[1] ?? null;
$id = $segmentos[2] ?? null;

// TODO: Implementar enrutamiento para:
// GET /api/colmenas         → listarColmenas()
// GET /api/colmenas/5       → obtenerColmena($id)
// POST /api/colmenas        → crearColmena()
// PUT /api/colmenas/5       → actualizarColmena($id)
// DELETE /api/colmenas/5    → eliminarColmena($id)

// Tu código aquí...
?>
```

**Solución:**
```php
<?php
if ($recurso === 'colmenas') {
    switch ($metodo) {
        case 'GET':
            if ($id) {
                obtenerColmena($id);
            } else {
                listarColmenas();
            }
            break;
        
        case 'POST':
            crearColmena();
            break;
        
        case 'PUT':
            if ($id) {
                actualizarColmena($id);
            }
            break;
        
        case 'DELETE':
            if ($id) {
                eliminarColmena($id);
            }
            break;
    }
}
?>
```

---

## 📝 Resumen

### ¿Qué hace `parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)`?

**Extrae SOLO la ruta de la URL**, sin incluir:
- ❌ Parámetros de consulta (?zona=Toledo)
- ❌ Fragmentos (#seccion)
- ❌ Dominio
- ❌ Protocolo

### ¿Por qué es importante en APIs REST?

1. **Enrutamiento limpio**: Permite identificar recursos sin contaminación de parámetros
2. **Separación de responsabilidades**: Ruta para recursos, query string para filtros
3. **Prevención de errores**: Evita que los parámetros interfieran con el parsing
4. **Estándar REST**: Sigue las mejores prácticas de diseño de APIs

### Flujo típico:

```
URL completa → parse_url() → Solo ruta → explode() → Segmentos → Enrutamiento
```

---

**¡Ahora entiendes completamente cómo funciona el enrutamiento en APIs REST! 🚀**
