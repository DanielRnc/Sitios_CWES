# API REST para Gestión de Colmenas Sensorizadas

## Descripción del Proyecto

API REST desarrollada en PHP puro (sin frameworks) siguiendo el patrón de diseño MVC para gestionar colmenas sensorizadas. Esta API permite a socios colaboradores acceder a los datos de las colmenas sin tener acceso directo a la base de datos.

## Características

- ✅ PHP Puro (sin frameworks)
- ✅ Patrón de diseño MVC
- ✅ Programación Orientada a Objetos (POO)
- ✅ API RESTful con formato JSON
- ✅ 4 Endpoints CRUD completos
- ✅ Validación de datos
- ✅ Códigos HTTP apropiados
- ✅ Manejo de errores
- ✅ CORS habilitado

## Estructura del Proyecto

```
api-colmenas/
├── config/
│   ├── config.php           # Configuración general y CORS
│   └── Database.php         # Clase de conexión PDO
├── models/
│   └── Colmena.php          # Modelo (M del MVC)
├── controllers/
│   └── ColmenaController.php # Controlador (C del MVC)
├── public/
│   ├── api.php              # Enrutador principal
│   └── .htaccess            # Configuración Apache
└── database.sql             # Script de base de datos
```

## Explicación del Patrón MVC en la API

### MODELO (Model)
**Archivo:** `models/Colmena.php`

- Representa los datos de las colmenas
- Interactúa directamente con la base de datos
- Contiene la lógica de negocio
- Métodos: `obtenerTodas()`, `obtenerPorId()`, `crear()`, `actualizar()`, `eliminar()`

### CONTROLADOR (Controller)
**Archivo:** `controllers/ColmenaController.php`

- Recibe las peticiones HTTP
- Procesa los datos recibidos
- Llama a los métodos del Modelo
- Devuelve respuestas JSON
- NO contiene lógica de negocio, solo coordina

### VISTA (View)
**En una API REST:** No hay vistas HTML tradicionales
- Las "vistas" son las respuestas JSON
- El controlador formatea los datos en JSON
- Los clientes de la API interpretan el JSON

## Requisitos del Sistema

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web Apache (con mod_rewrite)
- Extensión PDO de PHP
- Extensión JSON de PHP

## Instalación

### 1. Crear la Base de Datos

```bash
mysql -u root -p < database.sql
```

O importar desde phpMyAdmin el archivo `database.sql`

### 2. Configurar la Conexión

Editar `config/config.php` con tus credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'api_colmenas');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Copiar al Servidor Web

```bash
# Copiar a la carpeta del servidor web
cp -r api-colmenas /var/www/html/
# O en Windows con XAMPP
# Copiar a C:\xampp\htdocs\api-colmenas
```

### 4. Configurar Apache

Asegurarse de que `mod_rewrite` esté habilitado:

```bash
# En Linux
sudo a2enmod rewrite
sudo service apache2 restart
```

### 5. Acceder a la API

Base URL: `http://localhost/api-colmenas/public/api/colmenas`

## Endpoints de la API

### 1. Obtener todas las colmenas
```
GET /api/colmenas
```

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "total": 12,
    "data": [
        {
            "id": 1,
            "zona": "Campo de Criptana",
            "usuario_id": 1,
            "usuario_nombre": "Juan García López",
            "username": "apicultor1",
            "fecha_creacion": "2024-12-11 14:00:00"
        },
        ...
    ]
}
```

### 2. Obtener una colmena específica
```
GET /api/colmenas/{id}
```

**Ejemplo:**
```
GET /api/colmenas/1
```

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "zona": "Campo de Criptana",
        "usuario_id": 1,
        "usuario_nombre": "Juan García López",
        "fecha_creacion": "2024-12-11 14:00:00"
    }
}
```

**Respuesta error (404):**
```json
{
    "success": false,
    "message": "Colmena no encontrada"
}
```

### 3. Crear una nueva colmena
```
POST /api/colmenas
```

**Body (JSON):**
```json
{
    "zona": "Toledo",
    "usuario_id": 1
}
```

**Respuesta exitosa (201):**
```json
{
    "success": true,
    "message": "Colmena creada correctamente",
    "data": {
        "id": 13,
        "zona": "Toledo",
        "usuario_id": 1
    }
}
```

**Respuesta error validación (400):**
```json
{
    "success": false,
    "message": "Error de validación",
    "errores": [
        "El campo 'zona' es obligatorio"
    ]
}
```

### 4. Actualizar una colmena
```
PUT /api/colmenas/{id}
```

**Body (JSON):**
```json
{
    "zona": "Madrid",
    "usuario_id": 2
}
```

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "message": "Colmena actualizada correctamente",
    "data": {
        "id": 1,
        "zona": "Madrid",
        "usuario_id": 2
    }
}
```

### 5. Eliminar una colmena
```
DELETE /api/colmenas/{id}
```

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "message": "Colmena eliminada correctamente"
}
```

## Códigos de Estado HTTP Utilizados

| Código | Significado | Uso en la API |
|--------|-------------|---------------|
| 200 | OK | Operación exitosa (GET, PUT, DELETE) |
| 201 | Created | Recurso creado exitosamente (POST) |
| 400 | Bad Request | Datos inválidos o validación fallida |
| 404 | Not Found | Recurso no encontrado |
| 405 | Method Not Allowed | Método HTTP no soportado |
| 500 | Internal Server Error | Error del servidor |

## Pruebas con cURL

### Obtener todas las colmenas
```bash
curl -X GET http://localhost/api-colmenas/public/api/colmenas
```

### Obtener una colmena específica
```bash
curl -X GET http://localhost/api-colmenas/public/api/colmenas/1
```

### Crear una colmena
```bash
curl -X POST http://localhost/api-colmenas/public/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{"zona":"Toledo","usuario_id":1}'
```

### Actualizar una colmena
```bash
curl -X PUT http://localhost/api-colmenas/public/api/colmenas/1 \
  -H "Content-Type: application/json" \
  -d '{"zona":"Madrid","usuario_id":2}'
```

### Eliminar una colmena
```bash
curl -X DELETE http://localhost/api-colmenas/public/api/colmenas/1
```

## Pruebas con Postman

### Configuración
1. Crear nueva colección "API Colmenas"
2. Base URL: `http://localhost/api-colmenas/public/api/colmenas`

### Request GET - Todas las colmenas
- Method: GET
- URL: `{{base_url}}/colmenas`

### Request POST - Crear colmena
- Method: POST
- URL: `{{base_url}}/colmenas`
- Headers: `Content-Type: application/json`
- Body (raw JSON):
```json
{
    "zona": "Cuenca",
    "usuario_id": 3
}
```

### Request PUT - Actualizar colmena
- Method: PUT
- URL: `{{base_url}}/colmenas/1`
- Headers: `Content-Type: application/json`
- Body (raw JSON):
```json
{
    "zona": "Guadalajara",
    "usuario_id": 2
}
```

### Request DELETE - Eliminar colmena
- Method: DELETE
- URL: `{{base_url}}/colmenas/1`

## Validaciones Implementadas

La API valida:
- ✅ Campo `zona` es obligatorio
- ✅ Campo `zona` máximo 100 caracteres
- ✅ Campo `usuario_id` es obligatorio
- ✅ Campo `usuario_id` debe ser numérico y positivo
- ✅ Formato JSON válido en peticiones
- ✅ Existencia del recurso antes de actualizar/eliminar

## Seguridad

- ✅ Prepared Statements (PDO) para prevenir SQL Injection
- ✅ Sanitización de datos con `htmlspecialchars` y `strip_tags`
- ✅ Validación de tipos de datos
- ✅ Códigos HTTP apropiados
- ✅ CORS habilitado (configurable)

## Notas Didácticas

### Flujo de una Petición GET

1. Cliente hace petición: `GET /api/colmenas`
2. Apache redirige a `api.php` (vía .htaccess)
3. `api.php` analiza la ruta y método
4. Se instancia `ColmenaController`
5. Se llama a `obtenerTodas()`
6. El controlador llama a `$colmena->obtenerTodas()` (Modelo)
7. El modelo ejecuta query SQL y devuelve datos
8. El controlador formatea datos en JSON
9. Se envía respuesta JSON al cliente

### Flujo de una Petición POST

1. Cliente envía: `POST /api/colmenas` con JSON body
2. `api.php` identifica método POST
3. Controlador recibe datos con `file_get_contents("php://input")`
4. Controlador asigna datos al modelo
5. Modelo valida datos
6. Si válido: Modelo ejecuta INSERT
7. Controlador devuelve JSON con ID creado
8. Si inválido: Controlador devuelve errores en JSON

## Mejoras Futuras (Ejercicios para Estudiantes)

1. **Autenticación**: Implementar API Key o JWT
2. **Paginación**: Añadir `?page=1&limit=10`
3. **Filtros**: Añadir `?zona=Toledo&usuario_id=1`
4. **Logging**: Registrar todas las peticiones
5. **Rate Limiting**: Limitar peticiones por IP
6. **Versionado**: Implementar `/api/v2/colmenas`
7. **Documentación Swagger**: Generar docs automáticas
8. **Tests unitarios**: PHPUnit para testear endpoints

## Resolución de Problemas

### Error 404 en todas las peticiones
- Verificar que `mod_rewrite` esté habilitado
- Comprobar que `.htaccess` esté en la carpeta `public/`

### Error de conexión a BD
- Verificar credenciales en `config/config.php`
- Comprobar que MySQL esté corriendo
- Verificar que la base de datos `api_colmenas` existe

### Error "No se recibieron datos"
- Asegurarse de enviar header `Content-Type: application/json`
- Verificar que el JSON esté correctamente formateado

### CORS error desde navegador
- Verificar headers CORS en `config/config.php`
- Comprobar que Apache permite headers personalizados

## Contacto

Proyecto educativo para Ciclo Superior de Desarrollo de Aplicaciones Web.

---

**Desarrollado con 🐝 para enseñar APIs REST y MVC en PHP**
