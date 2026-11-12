# GUÍA DE INSTALACIÓN - API REST Colmenas

## 📋 Requisitos Previos

- PHP 7.4 o superior
- MySQL 5.7 o superior / MariaDB 10.3+
- Servidor web Apache con mod_rewrite
- Extensiones PHP: PDO, PDO_MySQL, JSON

### Verificar Requisitos

```bash
# Verificar versión de PHP
php -v

# Verificar extensiones PHP
php -m | grep -E "pdo|json"

# Verificar MySQL
mysql --version
```

## 🚀 INSTALACIÓN PASO A PASO

### PASO 1: Descargar el Proyecto

```bash
# Opción A: Descomprimir ZIP
unzip api-colmenas.zip

# Opción B: Clonar repositorio (si aplica)
# git clone https://github.com/tu-usuario/api-colmenas.git
```

### PASO 2: Ubicar en el Servidor Web

#### Para XAMPP (Windows)

```bash
# Copiar a la carpeta htdocs
Copiar carpeta api-colmenas a: C:\xampp\htdocs\

# Resultado: C:\xampp\htdocs\api-colmenas\
```

#### Para LAMP (Linux)

```bash
# Copiar al directorio web
sudo cp -r api-colmenas /var/www/html/

# Dar permisos
sudo chown -R www-data:www-data /var/www/html/api-colmenas
sudo chmod -R 755 /var/www/html/api-colmenas
```

#### Para MAMP (macOS)

```bash
# Copiar a la carpeta htdocs de MAMP
Copiar carpeta api-colmenas a: /Applications/MAMP/htdocs/

# Resultado: /Applications/MAMP/htdocs/api-colmenas/
```

### PASO 3: Crear la Base de Datos

#### Opción A: Usando phpMyAdmin (Recomendado)

1. Abrir phpMyAdmin: `http://localhost/phpmyadmin`
2. Hacer clic en "Nueva" (New) en el panel izquierdo
3. Nombre de la base de datos: `api_colmenas`
4. Cotejamiento: `utf8mb4_unicode_ci`
5. Hacer clic en "Crear"
6. Seleccionar la base de datos `api_colmenas`
7. Ir a la pestaña "Importar"
8. Seleccionar archivo: `api-colmenas/database.sql`
9. Hacer clic en "Continuar"
10. Verificar mensaje de éxito

#### Opción B: Usando MySQL en Terminal

```bash
# Conectar a MySQL
mysql -u root -p

# Ejecutar el script
mysql> source /ruta/completa/api-colmenas/database.sql

# O todo en un comando
mysql -u root -p < /ruta/completa/api-colmenas/database.sql
```

#### Verificar Instalación de BD

```bash
mysql -u root -p

mysql> USE api_colmenas;
mysql> SHOW TABLES;
# Debe mostrar: colmenas, usuarios

mysql> SELECT COUNT(*) FROM colmenas;
# Debe mostrar: 12

mysql> SELECT COUNT(*) FROM usuarios;
# Debe mostrar: 5

mysql> exit
```

### PASO 4: Configurar Credenciales de Base de Datos

Editar el archivo: `api-colmenas/config/config.php`

```php
<?php
// Ajustar estos valores según tu instalación
define('DB_HOST', 'localhost');        // Generalmente no cambia
define('DB_NAME', 'api_colmenas');      // No cambiar
define('DB_USER', 'root');              // Tu usuario de MySQL
define('DB_PASS', '');                  // Tu contraseña de MySQL
```

**Configuraciones comunes:**

- **XAMPP (Windows)**: usuario `root`, contraseña vacía `''`
- **LAMP (Linux)**: usuario `root`, contraseña según instalación
- **MAMP (Mac)**: usuario `root`, contraseña `root`

### PASO 5: Configurar Apache (mod_rewrite)

#### En XAMPP (Windows)

1. Abrir: `C:\xampp\apache\conf\httpd.conf`
2. Buscar y descomentar (quitar #):
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
3. Buscar `AllowOverride None` y cambiar a `AllowOverride All`
4. Guardar y reiniciar Apache desde el Panel de Control de XAMPP

#### En LAMP (Linux)

```bash
# Habilitar mod_rewrite
sudo a2enmod rewrite

# Editar configuración de Apache
sudo nano /etc/apache2/apache2.conf

# Buscar el bloque <Directory /var/www/>
# Cambiar AllowOverride None por AllowOverride All

# Reiniciar Apache
sudo service apache2 restart
```

#### En MAMP (Mac)

mod_rewrite generalmente ya está habilitado. Si hay problemas:

1. Editar: `/Applications/MAMP/conf/apache/httpd.conf`
2. Verificar que esté descomentado:
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
3. Reiniciar servidores desde MAMP

### PASO 6: Verificar Estructura de Archivos

```
api-colmenas/
├── config/
│   ├── config.php          ✓ Configurado
│   └── Database.php        ✓
├── models/
│   └── Colmena.php         ✓
├── controllers/
│   └── ColmenaController.php ✓
├── public/
│   ├── api.php             ✓ Punto de entrada
│   └── .htaccess           ✓ Configuración Apache
├── database.sql            ✓ Importado
└── README.md
```

### PASO 7: Iniciar Servidores

#### XAMPP
1. Abrir Panel de Control de XAMPP
2. Start → Apache
3. Start → MySQL
4. Verificar que ambos estén en verde

#### LAMP
```bash
sudo service apache2 start
sudo service mysql start

# Verificar estado
sudo service apache2 status
sudo service mysql status
```

#### MAMP
1. Abrir MAMP
2. Click en "Start Servers"
3. Verificar que los indicadores estén en verde

### PASO 8: Probar la API

#### Prueba Rápida en Navegador

```
http://localhost/api-colmenas/public/api/colmenas
```

**Respuesta esperada:**
```json
{
    "success": true,
    "total": 12,
    "data": [ ... ]
}
```

#### Prueba con cURL

```bash
curl http://localhost/api-colmenas/public/api/colmenas
```

#### Prueba con Postman

1. Abrir Postman
2. Crear nueva request
3. Method: GET
4. URL: `http://localhost/api-colmenas/public/api/colmenas`
5. Click "Send"
6. Verificar respuesta JSON con código 200

## ✅ VERIFICACIÓN COMPLETA

Ejecutar estas pruebas para verificar que todo funciona:

### Test 1: GET Todas las Colmenas

```bash
curl http://localhost/api-colmenas/public/api/colmenas
```
✓ Debe devolver 12 colmenas

### Test 2: GET Colmena Específica

```bash
curl http://localhost/api-colmenas/public/api/colmenas/1
```
✓ Debe devolver datos de colmena ID 1

### Test 3: POST Crear Colmena

```bash
curl -X POST http://localhost/api-colmenas/public/api/colmenas \
  -H "Content-Type: application/json" \
  -d '{"zona":"Test","usuario_id":1}'
```
✓ Debe crear colmena y devolver código 201

### Test 4: PUT Actualizar Colmena

```bash
curl -X PUT http://localhost/api-colmenas/public/api/colmenas/13 \
  -H "Content-Type: application/json" \
  -d '{"zona":"Test Actualizado","usuario_id":2}'
```
✓ Debe actualizar y devolver código 200

### Test 5: DELETE Eliminar Colmena

```bash
curl -X DELETE http://localhost/api-colmenas/public/api/colmenas/13
```
✓ Debe eliminar y devolver código 200

## 🔧 SOLUCIÓN DE PROBLEMAS

### Error: "No se puede conectar a la base de datos"

**Solución:**
1. Verificar que MySQL esté corriendo
2. Revisar credenciales en `config/config.php`
3. Verificar que la base de datos `api_colmenas` existe
   ```bash
   mysql -u root -p -e "SHOW DATABASES;"
   ```

### Error: 404 Not Found en todas las peticiones

**Causa:** mod_rewrite no está habilitado o .htaccess no funciona

**Solución:**
1. Verificar que mod_rewrite está habilitado
2. Verificar que `AllowOverride All` está configurado
3. Verificar que `.htaccess` está en `public/`
4. Reiniciar Apache

**Prueba alternativa (sin .htaccess):**
```
http://localhost/api-colmenas/public/api.php/api/colmenas
```

### Error: "Call to undefined function json_encode()"

**Causa:** Extensión JSON no instalada

**Solución:**
```bash
# Ubuntu/Debian
sudo apt-get install php-json

# CentOS/RHEL
sudo yum install php-json

# Reiniciar Apache
sudo service apache2 restart
```

### Error: PDOException "could not find driver"

**Causa:** Extensión PDO_MySQL no instalada

**Solución:**
```bash
# Ubuntu/Debian
sudo apt-get install php-mysql

# Verificar
php -m | grep pdo

# Reiniciar Apache
sudo service apache2 restart
```

### Error: Headers already sent

**Causa:** Espacios en blanco antes de `<?php` o después de `?>`

**Solución:**
1. Verificar que no haya espacios antes de `<?php` en archivos PHP
2. No usar `?>` al final de archivos PHP
3. Guardar archivos con codificación UTF-8 sin BOM

### Error: CORS en JavaScript

**Síntoma:** Error en consola del navegador sobre CORS

**Solución:**
Los headers CORS ya están en `config/config.php`. Si persiste:

```php
// Añadir al inicio de public/api.php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
```

### Error: "Undefined index" en URI

**Causa:** Configuración de .htaccess

**Solución:**
Modificar `public/api.php` línea ~25:

```php
// Cambiar:
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Por:
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
```

### La API funciona pero devuelve datos extraños

**Verificar encoding:**
```sql
-- En MySQL
SHOW VARIABLES LIKE 'character_set%';

-- Debe mostrar utf8mb4
```

**Verificar colación de tablas:**
```sql
USE api_colmenas;
SHOW TABLE STATUS;
```

## 🌐 CONFIGURACIÓN PARA PRODUCCIÓN

### 1. Deshabilitar Errores de PHP

En `config/config.php`:
```php
// Desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Producción
ini_set('display_errors', 0);
error_reporting(0);
```

### 2. Configurar CORS Específico

```php
// En vez de '*', usar dominio específico
header('Access-Control-Allow-Origin: https://tu-dominio.com');
```

### 3. Añadir Autenticación

Implementar API Key, JWT, o OAuth2

### 4. Limitar Rate Limiting

Limitar peticiones por IP

### 5. HTTPS

Usar certificado SSL/TLS en producción

## 📱 ACCESO DESDE OTROS DISPOSITIVOS

### En Red Local

1. Obtener IP del servidor:
   ```bash
   # Linux/Mac
   ifconfig | grep "inet "
   
   # Windows
   ipconfig
   ```

2. Acceder desde otro dispositivo:
   ```
   http://192.168.1.X/api-colmenas/public/api/colmenas
   ```

3. Configurar firewall para permitir puerto 80

## 📚 SIGUIENTES PASOS

1. ✓ API instalada y funcionando
2. Leer `README.md` para documentación completa
3. Leer `GUIA_API_REST.md` para conceptos teóricos
4. Probar con `EJEMPLOS_USO.md`
5. Implementar cliente que consuma la API

## 💡 RECOMENDACIONES

- Hacer backup de la base de datos regularmente
- Mantener PHP y MySQL actualizados
- Revisar logs de Apache para detectar errores
- Usar herramientas como Postman para desarrollo
- Implementar tests automatizados

## 🎓 RECURSOS ADICIONALES

- [Documentación PHP PDO](https://www.php.net/manual/es/book.pdo.php)
- [Guía REST de Mozilla](https://developer.mozilla.org/es/docs/Web/HTTP/Methods)
- [Tutorial MySQL](https://dev.mysql.com/doc/)

---

**¡Instalación completada! Ahora puedes comenzar a usar tu API REST 🚀**
