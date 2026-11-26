# GUÍA DIDÁCTICA: PATRÓN MVC EN PHP
## Sistema de Gestión de Tareas

### Documento para Profesores y Alumnos de Desarrollo de Aplicaciones Web

---

## 1. INTRODUCCIÓN

Este documento explica cómo se ha implementado el patrón de diseño **Modelo-Vista-Controlador (MVC)** en una aplicación web desarrollada en PHP con Programación Orientada a Objetos (POO). El proyecto consiste en un sistema de gestión de tareas para proyectos de desarrollo de software.

---

## 2. ¿QUÉ ES EL PATRÓN MVC?

El patrón **Modelo-Vista-Controlador** es un patrón de diseño arquitectónico que separa una aplicación en tres componentes principales:

### 2.1. MODELO (Model)
- **Responsabilidad**: Gestiona los datos y la lógica de negocio
- **Función**: Interactúa con la base de datos (operaciones CRUD)
- **Independencia**: No tiene conocimiento de las vistas ni los controladores
- **En nuestro proyecto**: Clases `Usuario.php` y `Tarea.php`

### 2.2. VISTA (View)
- **Responsabilidad**: Presenta la información al usuario
- **Función**: Genera la interfaz de usuario (HTML)
- **Independencia**: Solo recibe datos, no los procesa
- **En nuestro proyecto**: Archivos PHP en la carpeta `app/views/`

### 2.3. CONTROLADOR (Controller)
- **Responsabilidad**: Gestiona las peticiones del usuario
- **Función**: Coordina el modelo y la vista
- **Independencia**: Actúa como intermediario
- **En nuestro proyecto**: Clases en `app/controllers/`

### 2.4. DIAGRAMA DEL FLUJO MVC

```
Usuario → Solicitud HTTP
    ↓
INDEX.PHP (Enrutador)
    ↓
CONTROLADOR
    ↓ (solicita datos)
MODELO → Base de Datos
    ↓ (devuelve datos)
CONTROLADOR
    ↓ (pasa datos)
VISTA → Genera HTML
    ↓
Usuario ← Respuesta HTML
```

---

## 3. ESTRUCTURA DEL PROYECTO

```
proyecto-mvc-tareas/
│
├── index.php                    # Punto de entrada (Front Controller)
│
├── config/
│   └── database.php             # Configuración de base de datos
│
├── app/
│   ├── models/                  # MODELOS
│   │   ├── Usuario.php
│   │   └── Tarea.php
│   │
│   ├── controllers/             # CONTROLADORES
│   │   ├── AuthController.php
│   │   ├── TareaController.php
│   │   └── PublicController.php
│   │
│   └── views/                   # VISTAS
│       ├── public/
│       │   └── home.php
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       └── user/
│           ├── mis_tareas.php
│           ├── crear_tarea.php
│           └── editar_tarea.php
│
├── public/
│   └── css/
│       └── style.css            # Estilos CSS
│
└── database.sql                 # Script SQL
```

---

## 4. PROGRAMACIÓN ORIENTADA A OBJETOS EN PHP

### 4.1. ¿Qué es la POO?

La **Programación Orientada a Objetos** es un paradigma de programación que organiza el código en **objetos** que contienen datos (propiedades) y funciones (métodos).

### 4.2. Conceptos Fundamentales

#### a) CLASE
Una clase es una plantilla o molde que define las propiedades y métodos que tendrán los objetos.

```php
class Usuario {
    // Propiedades (atributos)
    public $id;
    public $nombre;
    public $email;
    private $password;
    
    // Método constructor
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Métodos (funciones)
    public function registrar() {
        // Código para registrar usuario
    }
}
```

#### b) OBJETO
Un objeto es una instancia de una clase. Se crea usando la palabra clave `new`.

```php
// Crear un objeto de la clase Usuario
$usuario = new Usuario($db);

// Asignar valores a las propiedades
$usuario->nombre = "Juan Pérez";
$usuario->email = "juan@ejemplo.com";

// Llamar a un método
$usuario->registrar();
```

#### c) MODIFICADORES DE ACCESO

- **public**: Accesible desde cualquier lugar
- **private**: Solo accesible dentro de la clase
- **protected**: Accesible en la clase y clases heredadas

```php
class Ejemplo {
    public $publico = "Todos pueden ver esto";
    private $privado = "Solo esta clase puede ver esto";
    protected $protegido = "Esta clase y sus hijas pueden ver esto";
}
```

#### d) $this
La palabra clave `$this` hace referencia al objeto actual.

```php
class Usuario {
    public $nombre;
    
    public function saludar() {
        // $this->nombre hace referencia a la propiedad del objeto actual
        echo "Hola, soy " . $this->nombre;
    }
}
```

### 4.3. Ejemplo Práctico: Clase Usuario

Analicemos la clase `Usuario` de nuestro proyecto:

```php
class Usuario {
    // PROPIEDADES
    private $conn;              // Conexión a BD (privada)
    private $table_name = "usuarios";
    public $id;
    public $nombre;
    public $email;
    public $password;
    
    // CONSTRUCTOR
    // Se ejecuta automáticamente al crear un objeto
    public function __construct($db) {
        $this->conn = $db;      // Asigna la conexión
    }
    
    // MÉTODO: Registrar usuario
    public function registrar() {
        // Preparar consulta SQL
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, email, password) 
                  VALUES (:nombre, :email, :password)";
        
        $stmt = $this->conn->prepare($query);
        
        // Usar propiedades del objeto con $this
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        // ...
        
        return $stmt->execute();
    }
    
    // MÉTODO: Verificar si email existe
    public function emailExiste() {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE email = :email";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
```

**¿Cómo se usa esta clase?**

```php
// 1. Crear conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// 2. Crear objeto Usuario
$usuario = new Usuario($db);

// 3. Asignar valores a las propiedades
$usuario->nombre = "María García";
$usuario->email = "maria@ejemplo.com";
$usuario->password = "123456";

// 4. Llamar a métodos
if ($usuario->emailExiste()) {
    echo "El email ya está registrado";
} else {
    $usuario->registrar();
}
```

---

## 5. IMPLEMENTACIÓN DEL PATRÓN MVC EN EL PROYECTO

### 5.1. EL PUNTO DE ENTRADA: index.php

El archivo `index.php` es el **Front Controller** (Controlador Frontal). Todas las peticiones pasan por aquí.

**Funciones principales:**
1. Iniciar la sesión PHP
2. Incluir los archivos necesarios
3. Leer el parámetro `action` de la URL
4. Enrutar la petición al controlador correspondiente

```php
// Ejemplo de URL: index.php?action=login
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

switch($action) {
    case 'login':
        $authController->mostrarLogin();
        break;
    
    case 'procesarLogin':
        $authController->procesarLogin();
        break;
    
    // ... más casos
}
```

### 5.2. LOS MODELOS (Capa de Datos)

Los modelos representan las entidades del sistema y gestionan las operaciones con la base de datos.

#### Ejemplo: Clase Tarea

```php
class Tarea {
    // Propiedades que representan los campos de la tabla
    public $id;
    public $titulo;
    public $descripcion;
    public $fase;
    public $estado;
    public $usuario_id;
    
    // Constructor que recibe la conexión
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Método para obtener todas las tareas
    public function obtenerTodas() {
        $query = "SELECT * FROM tareas 
                  ORDER BY fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Método para crear una tarea
    public function crear() {
        $query = "INSERT INTO tareas 
                  (titulo, descripcion, fase, estado, usuario_id)
                  VALUES (:titulo, :descripcion, :fase, :estado, :usuario_id)";
        
        $stmt = $this->conn->prepare($query);
        
        // Vincular parámetros con propiedades del objeto
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        // ... más parámetros
        
        return $stmt->execute();
    }
}
```

**Características de los Modelos:**
- Contienen la lógica de acceso a datos
- Usan PDO para interactuar con MySQL
- Validan y sanitizan los datos
- No conocen las vistas ni los controladores

### 5.3. LOS CONTROLADORES (Capa de Lógica)

Los controladores reciben las peticiones, interactúan con los modelos y cargan las vistas.

#### Ejemplo: TareaController

```php
class TareaController {
    
    // Método para mostrar las tareas del usuario
    public function misTareas() {
        // 1. INTERACTUAR CON EL MODELO
        $database = new Database();
        $db = $database->getConnection();
        $tarea = new Tarea($db);
        
        $tarea->usuario_id = $_SESSION['usuario_id'];
        $stmt = $tarea->obtenerPorUsuario();
        $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 2. CARGAR LA VISTA (pasando los datos)
        require_once 'app/views/user/mis_tareas.php';
    }
    
    // Método para guardar una tarea
    public function guardar() {
        // 1. VALIDAR DATOS DEL FORMULARIO
        if (empty($_POST['titulo'])) {
            $_SESSION['error'] = "El título es obligatorio";
            header("Location: index.php?action=crearTarea");
            exit();
        }
        
        // 2. INTERACTUAR CON EL MODELO
        $database = new Database();
        $db = $database->getConnection();
        $tarea = new Tarea($db);
        
        $tarea->titulo = $_POST['titulo'];
        $tarea->descripcion = $_POST['descripcion'];
        // ... más asignaciones
        
        // 3. EJECUTAR LA ACCIÓN
        if ($tarea->crear()) {
            $_SESSION['mensaje'] = "Tarea creada";
            header("Location: index.php?action=misTareas");
        }
    }
}
```

**Características de los Controladores:**
- Gestionan el flujo de la aplicación
- Validan los datos de entrada
- Coordinan modelos y vistas
- Gestionan redirecciones

### 5.4. LAS VISTAS (Capa de Presentación)

Las vistas son archivos PHP que generan el HTML que ve el usuario.

#### Ejemplo: mis_tareas.php

```php
<!DOCTYPE html>
<html>
<head>
    <title>Mis Tareas</title>
</head>
<body>
    <h1>Mis Tareas</h1>
    
    <?php if (count($tareas) > 0): ?>
        <table>
            <?php foreach($tareas as $tarea): ?>
                <tr>
                    <td><?php echo htmlspecialchars($tarea['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($tarea['fase']); ?></td>
                    <td>
                        <a href="index.php?action=editarTarea&id=<?php echo $tarea['id']; ?>">
                            Editar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No tienes tareas</p>
    <?php endif; ?>
</body>
</html>
```

**Características de las Vistas:**
- Solo presentan información
- Reciben datos de los controladores
- Usan `htmlspecialchars()` para seguridad
- No contienen lógica de negocio

---

## 6. FLUJO COMPLETO DE UNA PETICIÓN

Veamos el flujo completo cuando un usuario crea una nueva tarea:

### Paso 1: Usuario accede al formulario
```
URL: index.php?action=crearTarea
```

### Paso 2: index.php enruta al controlador
```php
case 'crearTarea':
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php?action=login");
        exit();
    }
    $tareaController->crear();
    break;
```

### Paso 3: Controlador carga la vista
```php
public function crear() {
    require_once 'app/views/user/crear_tarea.php';
}
```

### Paso 4: Vista muestra el formulario
```html
<form action="index.php?action=guardarTarea" method="POST">
    <input type="text" name="titulo">
    <select name="fase">...</select>
    <button type="submit">Guardar</button>
</form>
```

### Paso 5: Usuario envía el formulario
```
POST a index.php?action=guardarTarea
```

### Paso 6: index.php enruta al método guardar
```php
case 'guardarTarea':
    $tareaController->guardar();
    break;
```

### Paso 7: Controlador valida y usa el modelo
```php
public function guardar() {
    // Validar datos
    if (empty($_POST['titulo'])) {
        // Manejar error
    }
    
    // Crear objeto del modelo
    $tarea = new Tarea($db);
    $tarea->titulo = $_POST['titulo'];
    // ...
    
    // Guardar en la BD
    if ($tarea->crear()) {
        $_SESSION['mensaje'] = "Tarea creada";
        header("Location: index.php?action=misTareas");
    }
}
```

### Paso 8: Modelo guarda en la base de datos
```php
public function crear() {
    $query = "INSERT INTO tareas (...) VALUES (...)";
    $stmt = $this->conn->prepare($query);
    // ...
    return $stmt->execute();
}
```

### Paso 9: Redirección a la lista de tareas
El usuario ve su nueva tarea en la lista.

---

## 7. GESTIÓN DE SESIONES EN PHP

La aplicación usa sesiones para mantener el estado del usuario logueado.

### 7.1. Iniciar Sesión
```php
session_start();  // En index.php
```

### 7.2. Guardar Datos en la Sesión
```php
// Después del login exitoso
$_SESSION['usuario_id'] = $usuario->id;
$_SESSION['usuario_nombre'] = $usuario->nombre;
```

### 7.3. Verificar si el Usuario está Logueado
```php
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?action=login");
    exit();
}
```

### 7.4. Cerrar Sesión
```php
session_unset();   // Eliminar variables
session_destroy(); // Destruir sesión
```

### 7.5. Usar Datos de la Sesión en las Vistas
```php
<?php if (isset($_SESSION['usuario_id'])): ?>
    <p>Hola, <?php echo $_SESSION['usuario_nombre']; ?></p>
<?php endif; ?>
```

---

## 8. SEGURIDAD IMPLEMENTADA

### 8.1. Protección contra Inyección SQL
Usamos **consultas preparadas** con PDO:

```php
// MAL (vulnerable a SQL injection)
$query = "SELECT * FROM usuarios WHERE email = '$email'";

// BIEN (seguro)
$query = "SELECT * FROM usuarios WHERE email = :email";
$stmt = $this->conn->prepare($query);
$stmt->bindParam(":email", $email);
```

### 8.2. Protección XSS (Cross-Site Scripting)
Usamos `htmlspecialchars()` al mostrar datos:

```php
// En las vistas
echo htmlspecialchars($tarea['titulo']);
```

### 8.3. Hash de Contraseñas
```php
// Al registrar
$password_hash = password_hash($this->password, PASSWORD_DEFAULT);

// Al verificar login
password_verify($password_introducida, $this->password);
```

### 8.4. Sanitización de Datos
```php
$this->titulo = htmlspecialchars(strip_tags($this->titulo));
```

---

## 9. VENTAJAS DEL PATRÓN MVC

1. **Separación de responsabilidades**: Cada componente tiene una función clara
2. **Mantenibilidad**: Es fácil encontrar y modificar código
3. **Reutilización**: Los modelos pueden usarse en diferentes controladores
4. **Trabajo en equipo**: Diferentes desarrolladores pueden trabajar en paralelo
5. **Testabilidad**: Es más fácil hacer pruebas unitarias
6. **Escalabilidad**: Facilita añadir nuevas funcionalidades

---

## 10. EJERCICIOS PROPUESTOS PARA LOS ALUMNOS

### Ejercicio 1: Añadir Campo de Prioridad
Modificar la tabla `tareas` para incluir un campo `prioridad` (Alta, Media, Baja) y actualizar el CRUD.

### Ejercicio 2: Filtrar Tareas por Estado
Añadir un filtro en "Mis Tareas" para ver solo tareas pendientes, en progreso o completadas.

### Ejercicio 3: Perfil de Usuario
Crear una página donde el usuario pueda ver y editar su perfil (nombre, email).

### Ejercicio 4: Búsqueda de Tareas
Implementar un buscador de tareas por título o descripción.

### Ejercicio 5: Estadísticas
Crear una vista que muestre estadísticas: total de tareas, tareas por fase, tareas por estado.

---

## 11. INSTALACIÓN Y CONFIGURACIÓN

### Requisitos:
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache o Nginx)

### Pasos:

1. **Crear la base de datos**:
   ```bash
   mysql -u root -p < database.sql
   ```

2. **Configurar la conexión**:
   Editar `config/database.php` con tus credenciales:
   ```php
   private $host = "localhost";
   private $db_name = "tareas_db";
   private $username = "tu_usuario";
   private $password = "tu_password";
   ```

3. **Iniciar el servidor**:
   ```bash
   php -S localhost:8000
   ```

4. **Acceder a la aplicación**:
   ```
   http://localhost:8000
   ```

### Usuarios de Prueba:
- Email: `juan@ejemplo.com` | Password: `123456`
- Email: `maria@ejemplo.com` | Password: `123456`

---

## 12. CONCLUSIÓN

Este proyecto demuestra cómo implementar el patrón MVC en PHP de forma didáctica y práctica. Los alumnos aprenderán:

- La arquitectura MVC y sus beneficios
- Programación Orientada a Objetos en PHP
- Gestión de sesiones y autenticación
- Operaciones CRUD con bases de datos
- Buenas prácticas de seguridad web

El código está comentado extensivamente para facilitar el aprendizaje y puede servir como base para proyectos más complejos.

---

**Autor**: Proyecto Didáctico - Desarrollo de Aplicaciones Web
**Fecha**: 2024
**Licencia**: Uso educativo libre
