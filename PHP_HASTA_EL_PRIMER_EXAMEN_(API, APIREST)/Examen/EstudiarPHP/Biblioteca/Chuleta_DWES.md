# Chuleta examen DWES PHP MVC

## 1. Receta: añadir algo nuevo al MVC

Cualquier ejercicio se resuelve tocando estas piezas en este orden, probando al final de cada una.

1. **Base de datos**: si hace falta, la columna o la tabla nueva en phpMyAdmin.
2. **Modelo**: el método con el SQL. Todo el SQL va aquí y en ningún otro sitio.
3. **Controlador**: el método que lee `$_GET` o `$_POST`, llama al modelo y carga una vista o redirige.
4. **Vista**: solo HTML y `<?= ?>`. Nada de SQL.
5. **index.php**: un `case` nuevo con `new Controlador($db)` y la llamada al método.
6. **Enlace o botón** en otra vista que lleve a `index.php?action=nueva`.
7. **API**: si lo piden en JSON, método en el ApiController y su caso en api.php.

El camino de una petición: vista con enlace o formulario → index.php → controlador → modelo → base de datos → vuelta al controlador → vista o `header('Location: ...')`.

Si el botón no hace nada o sale Página no encontrada: mira el `case` del index y que el `action` del enlace se escriba igual.

## 2. Estructura, SQL y conexión

```text
proyecto/
├── config/       config.php y Database.php
├── models/       una clase por tabla
├── controllers/  AuthController, XController, ApiXController
├── views/        auth/ y una carpeta por recurso
├── api/          api.php y .htaccess
├── index.php     enrutador de la web
└── database.sql
```

**database.sql**

```sql
SET NAMES utf8mb4;
DROP DATABASE IF EXISTS biblioteca;
CREATE DATABASE biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    genero ENUM('Ficción', 'Otro'),
    leido BOOLEAN DEFAULT FALSE,
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

-- Añadir una columna a una tabla que ya existe
ALTER TABLE libros ADD COLUMN paginas INT;
```

**config/config.php**

```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'biblioteca');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
```

**config/Database.php**

```php
<?php
class Database
{
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $charset = DB_CHARSET;
    private $conn = null;

    public function getConnection()
    {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Error de conexión: " . $e->getMessage());
                return null;
            }
        }
        return $this->conn;
    }
}
```

Para ver el error real de conexión: cambia `error_log(...)` por `echo $e->getMessage();` y recarga.

## 3. Modelo: plantilla con los 5 tipos de consulta

Un modelo por tabla. Recibe `$db` en el constructor. Cambia `Libro` y los campos por los del ejercicio.

```php
<?php
class Libro
{
    private $conn;

    // Propiedades = columnas de la tabla
    public $id;
    public $titulo;
    public $leido;
    public $usuario_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Validar: devuelve un array de errores
    public function validar()
    {
        $errores = [];
        if (empty($this->titulo)) $errores[] = 'El título es obligatorio';
        return $errores;
    }

    // SELECT de varios -> fetchAll devuelve array de filas
    public function listarPorUsuario($usuario_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM libros WHERE usuario_id = :u");
        $stmt->bindParam(':u', $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // SELECT de uno -> fetch devuelve una fila o false
    public function obtenerPorId($id, $usuario_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM libros WHERE id = :id AND usuario_id = :u");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':u', $usuario_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // INSERT
    public function crear()
    {
        $stmt = $this->conn->prepare("INSERT INTO libros (titulo, leido, usuario_id) VALUES (:t, :l, :u)");
        $stmt->bindParam(':t', $this->titulo);
        $stmt->bindParam(':l', $this->leido);
        $stmt->bindParam(':u', $this->usuario_id);
        return $stmt->execute();
    }

    // UPDATE, solo si es del usuario
    public function actualizar()
    {
        $stmt = $this->conn->prepare("UPDATE libros SET titulo = :t, leido = :l WHERE id = :id AND usuario_id = :u");
        $stmt->bindParam(':t', $this->titulo);
        $stmt->bindParam(':l', $this->leido);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':u', $this->usuario_id);
        return $stmt->execute();
    }

    // DELETE -> rowCount dice cuántas filas borró
    public function eliminar($id, $usuario_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM libros WHERE id = :id AND usuario_id = :u");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':u', $usuario_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Invertir un campo de sí o no: NOT 0 = 1 y NOT 1 = 0
    public function cambiarLeido($id, $usuario_id)
    {
        $stmt = $this->conn->prepare("UPDATE libros SET leido = NOT leido WHERE id = :id AND usuario_id = :u");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':u', $usuario_id);
        return $stmt->execute();
    }
}
```

**Forma corta equivalente**, con `?` en vez de nombres. El orden del array tiene que ser el de los `?`:

```php
$stmt = $this->conn->prepare("SELECT * FROM libros WHERE id = ? AND usuario_id = ?");
$stmt->execute([$id, $usuario_id]);
return $stmt->fetch();
```

**Filtro opcional** que se añade solo si viene el dato:

```php
$sql = "SELECT * FROM libros WHERE 1=1";
$params = [];
if (!empty($genero)) {
    $sql .= " AND genero = ?";
    $params[] = $genero;
}
$stmt = $this->conn->prepare($sql);
$stmt->execute($params);
return $stmt->fetchAll();
```

Columna con ñ o tilde: entre comillas invertidas en el SQL, `` `año_publicacion` ``. Al leerla en PHP: `$fila['año_publicacion']`.

## 4. index.php: el enrutador

Todo pasa por aquí. Lee `$_GET['action']` y llama al método del controlador. Una acción nueva = un `case` nuevo antes del `default`.

```php
<?php
session_start();   // siempre lo primero

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'models/Usuario.php';
require_once 'models/Libro.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/LibroController.php';

$db = (new Database())->getConnection();

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'login':
        $controller = new AuthController($db);
        $controller->login();
        break;

    case 'listar':
        $controller = new LibroController($db);
        $controller->listar();
        break;

    // ... un case por acción ...

    default:
        echo 'Página no encontrada';
        break;
}
```

Modelo o controlador nuevo = su `require_once` arriba. Si falta: Class not found.

Para ver errores escondidos, debajo de `<?php`:

```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## 5. Controlador web

El controlador recoge datos, llama al modelo y carga una vista o redirige. Nunca hace SQL.

**Constructor con protección de rutas**: protege todas las acciones de golpe.

```php
<?php
class LibroController
{
    private $libro;
    private $usuario;

    public function __construct($db)
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $this->libro = new Libro($db);
        $this->usuario = new Usuario($db);
    }
```

**Listar**: pide datos al modelo y carga la vista. Las variables creadas aquí se ven en la vista.

```php
    public function listar()
    {
        $libros = $this->libro->listarPorUsuario($_SESSION['usuario_id']);
        require 'views/libros/listar.php';
    }
```

**Crear**: el mismo método muestra el formulario con GET y lo procesa con POST. Es la plantilla para casi todo.

```php
    public function crear()
    {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->libro->titulo = trim($_POST['titulo']);
            $this->libro->leido = isset($_POST['leido']) ? 1 : 0;   // checkbox
            $this->libro->usuario_id = $_SESSION['usuario_id'];

            $errores = $this->libro->validar();

            if (empty($errores)) {
                $this->libro->crear();
                header('Location: index.php?action=listar');
                exit;
            }
        }
        require 'views/libros/crear.php';
    }
```

**Editar**: carga el registro por el id de la URL; si no es del usuario, fuera.

```php
    public function editar()
    {
        $errores = [];
        $id = $_GET['id'];

        $libro = $this->libro->obtenerPorId($id, $_SESSION['usuario_id']);
        if (!$libro) {
            header('Location: index.php?action=listar');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->libro->id = $id;
            $this->libro->titulo = trim($_POST['titulo']);
            $this->libro->leido = isset($_POST['leido']) ? 1 : 0;
            $this->libro->usuario_id = $_SESSION['usuario_id'];

            $errores = $this->libro->validar();
            if (empty($errores)) {
                $this->libro->actualizar();
                header('Location: index.php?action=listar');
                exit;
            }
        }
        require 'views/libros/editar.php';
    }
```

**Eliminar**: sin vista, por POST.

```php
    public function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->libro->eliminar($_POST['id'], $_SESSION['usuario_id']);
        }
        header('Location: index.php?action=listar');
        exit;
    }
```

**Transferir**: igual que editar, pero el formulario solo trae el destino.

```php
    public function transferir()
    {
        $id = $_GET['id'];
        $libro = $this->libro->obtenerPorId($id, $_SESSION['usuario_id']);
        if (!$libro) {
            header('Location: index.php?action=listar');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->libro->transferir($id, $_SESSION['usuario_id'], $_POST['destino_id']);
            header('Location: index.php?action=listar');
            exit;
        }
        $usuarios = $this->usuario->listarOtros($_SESSION['usuario_id']);
        require 'views/libros/transferir.php';
    }
}
```

**Acción rápida sin formulario**, por ejemplo marcar leído con un enlace `index.php?action=marcadoLeido&id=3`:

```php
    public function marcadoLeido()
    {
        $this->libro->cambiarLeido($_GET['id'], $_SESSION['usuario_id']);
        header('Location: index.php?action=listar');
        exit;
    }
```

## 6. Vistas

Solo HTML y `<?= ?>`. Usan las variables que creó el controlador antes del `require`. Sin JavaScript y con CSS mínimo.

**Errores de validación**, al principio de cualquier formulario:

```php
<?php foreach ($errores as $error): ?>
    <p><?= $error ?></p>
<?php endforeach; ?>
```

**Tabla con un foreach** y la columna de acciones. Cada `<th>` de la cabecera va en la misma posición que su `<td>`:

```php
<p>Hola, <?= $_SESSION['usuario_nombre'] ?> | <a href="index.php?action=logout">Cerrar sesión</a></p>
<p><a href="index.php?action=crear">Añadir</a></p>

<?php if (empty($libros)): ?>
    <p>No hay libros.</p>
<?php else: ?>
    <table border="1">
        <tr><th>Título</th><th>Leído</th><th>Fecha</th><th>Acciones</th></tr>
        <?php foreach ($libros as $libro): ?>
            <tr>
                <td><?= $libro['titulo'] ?></td>
                <td><?= $libro['leido'] ? 'Sí' : 'No' ?></td>
                <td><?= $libro['fecha_creacion'] ?></td>
                <td>
                    <a href="index.php?action=editar&id=<?= $libro['id'] ?>">Editar</a>
                    <a href="index.php?action=marcadoLeido&id=<?= $libro['id'] ?>"><?= $libro['leido'] ? 'Marcar no leído' : 'Marcar leído' ?></a>
                    <form method="post" action="index.php?action=eliminar">
                        <input type="hidden" name="id" value="<?= $libro['id'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
```

**Formulario de crear**, vacío:

```php
<form method="post" action="index.php?action=crear">
    <p>Título: <input type="text" name="titulo"></p>
    <p>Género:
        <select name="genero">
            <option>Ficción</option>
            <option>Otro</option>
        </select>
    </p>
    <p><input type="checkbox" name="leido"> Leído</p>
    <p><button type="submit">Guardar</button></p>
</form>
```

**Formulario de editar**, relleno con value, selected y checked. El id viaja en la URL del action:

```php
<form method="post" action="index.php?action=editar&id=<?= $libro['id'] ?>">
    <p>Título: <input type="text" name="titulo" value="<?= $libro['titulo'] ?>"></p>
    <select name="genero">
        <?php foreach (['Ficción', 'Otro'] as $g): ?>
            <option <?= $libro['genero'] === $g ? 'selected' : '' ?>><?= $g ?></option>
        <?php endforeach; ?>
    </select>
    <p><input type="checkbox" name="leido" <?= $libro['leido'] ? 'checked' : '' ?>> Leído</p>
    <p><button type="submit">Guardar</button></p>
</form>
```

**Desplegable con datos de la base de datos**, por ejemplo usuarios para transferir:

```php
<select name="destino_id">
    <?php foreach ($usuarios as $u): ?>
        <option value="<?= $u['id'] ?>"><?= $u['nombre'] ?></option>
    <?php endforeach; ?>
</select>
```

## 7. Login: registro, login y logout

El hash se hace en el modelo al registrar con `password_hash`, y se comprueba al entrar con `password_verify`. La sesión se llena en el controlador.

**Modelo Usuario**

```php
public function existeEmail()
{
    $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $this->email);
    $stmt->execute();
    return $stmt->rowCount() > 0;
}

public function registrar()
{
    $stmt = $this->conn->prepare("INSERT INTO usuarios (email, nombre, password) VALUES (:e, :n, :p)");
    $hash = password_hash($this->password, PASSWORD_DEFAULT);
    $stmt->bindParam(':e', $this->email);
    $stmt->bindParam(':n', $this->nombre);
    $stmt->bindParam(':p', $hash);
    return $stmt->execute();
}

public function login()
{
    $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :e");
    $stmt->bindParam(':e', $this->email);
    $stmt->execute();
    $fila = $stmt->fetch();
    if ($fila && password_verify($this->password, $fila['password'])) {
        $this->id = $fila['id'];
        $this->nombre = $fila['nombre'];
        return true;
    }
    return false;
}

public function listarOtros($id)
{
    $stmt = $this->conn->prepare("SELECT id, nombre, email FROM usuarios WHERE id != :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll();
}
```

**AuthController**

```php
public function registro()
{
    $errores = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $this->usuario->email = trim($_POST['email']);
        $this->usuario->nombre = trim($_POST['nombre']);
        $this->usuario->password = $_POST['password'];

        if ($this->usuario->existeEmail())             $errores[] = 'Email ya registrado';
        if (strlen($this->usuario->password) < 6)      $errores[] = 'Mínimo 6 caracteres';
        if ($this->usuario->password !== $_POST['confirmar']) $errores[] = 'No coinciden';

        if (empty($errores)) {
            $this->usuario->registrar();
            header('Location: index.php?action=login');
            exit;
        }
    }
    require 'views/auth/registro.php';
}

public function login()
{
    $errores = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $this->usuario->email = trim($_POST['email']);
        $this->usuario->password = $_POST['password'];
        if ($this->usuario->login()) {
            $_SESSION['usuario_id'] = $this->usuario->id;
            $_SESSION['usuario_nombre'] = $this->usuario->nombre;
            header('Location: index.php?action=listar');
            exit;
        }
        $errores[] = 'Email o contraseña incorrectos';
    }
    require 'views/auth/login.php';
}

public function logout()
{
    $_SESSION = [];
    session_destroy();
    header('Location: index.php?action=login');
    exit;
}
```

- Para guardar algo más en la sesión, como el email, se añade en `login()` del controlador y en `login()` del modelo.
- El campo email del formulario es `type="text"` si hay que registrar un usuario como `admin`.
- Una sesión se usa en cualquier archivo porque el index hace `session_start()` antes de todo.

## 8. API REST en JSON

La URL dice el recurso y el método HTTP dice qué hacer. api.php hace de index, y el controlador devuelve JSON en vez de cargar vistas.

| Qué | Método | URL | Cuerpo JSON | Código |
| --- | --- | --- | --- | --- |
| Todos | GET | api/libros | | 200 |
| Uno | GET | api/libros/1 | | 200 o 404 |
| Crear | POST | api/libros | `{"titulo":"Dune","usuario_id":1}` | 201 o 400 |
| Actualizar | PUT | api/libros/1 | `{"titulo":"Otro"}` | 200 o 404 |
| Borrar | DELETE | api/libros/1 | | 200 o 404 |
| Transferir | PATCH | api/libros/1/transferir | `{"usuario_id":2}` | 200 o 400 |

Sin .htaccess activo: `api/api.php/libros/1`.

**api/.htaccess**

```text
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ api.php [QSA,L]
```

**api/api.php**

```php
<?php
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Libro.php';
require_once __DIR__ . '/../controllers/ApiLibroController.php';

$metodo = $_SERVER['REQUEST_METHOD'];

// Trocear la URL y buscar el recurso
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$seg = explode('/', $uri);
$pos = array_search('libros', $seg);
if ($pos === false) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Recurso no encontrado']);
    exit;
}
$id = $seg[$pos + 1] ?? null;
$accion = $seg[$pos + 2] ?? null;

$db = (new Database())->getConnection();
$controller = new ApiLibroController($db);

switch ($metodo) {
    case 'GET':    $id ? $controller->obtenerPorId($id) : $controller->obtenerTodos(); break;
    case 'POST':   $controller->crear(); break;
    case 'PUT':    $controller->actualizar($id); break;
    case 'DELETE': $controller->eliminar($id); break;
    case 'PATCH':  if ($accion === 'transferir') $controller->transferir($id); break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
```

**ApiLibroController**: la función respuesta y un ejemplo con cuerpo JSON.

```php
private function respuesta($codigo, $datos)
{
    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

public function obtenerTodos()
{
    $libros = $this->libro->obtenerTodos();
    $this->respuesta(200, ['success' => true, 'total' => count($libros), 'data' => $libros]);
}

public function crear()
{
    $data = json_decode(file_get_contents("php://input"));   // objeto: $data->titulo
    if (empty($data)) {
        $this->respuesta(400, ['success' => false, 'message' => 'JSON no válido']);
    }
    $this->libro->titulo = $data->titulo ?? '';
    $this->libro->usuario_id = $data->usuario_id ?? '';

    $errores = $this->libro->validar();
    if (!empty($errores)) {
        $this->respuesta(400, ['success' => false, 'errores' => $errores]);
    }
    $this->libro->crear();
    $this->respuesta(201, ['success' => true, 'message' => 'Creado']);
}
```

- Parámetro de filtro en la URL, como `api/libros?genero=Ficción`: se lee con `$_GET['genero'] ?? null` en el controlador.
- Cabecera propia, como una API Key: `$_SERVER['HTTP_X_API_KEY']`. Si no vale, `respuesta(401, ...)`.
- **EchoAPI**: elige el método, pon la URL, en Body elige JSON y escribe el cuerpo, y pulsa Send. Abajo salen el JSON y el código.

## 9. Funciones clave de PHP

| Función | Para qué | Ejemplo |
| --- | --- | --- |
| `session_start()` | Abrir o reanudar la sesión | Primera línea del index.php |
| `$_SESSION['x']` | Guardar o leer datos del usuario logueado | `$_SESSION['usuario_id'] = $id;` |
| `session_destroy()` | Cerrar sesión | Tras `$_SESSION = [];` |
| `password_hash()` | Cifrar al registrar | `password_hash($pass, PASSWORD_DEFAULT)` |
| `password_verify()` | Comprobar al entrar | `password_verify($pass, $fila['password'])` |
| `header('Location: ...')` | Redirigir, siempre seguido de `exit;` | `header('Location: index.php?action=listar');` |
| `$_SERVER['REQUEST_METHOD']` | Saber si es GET, POST, PUT... | `=== 'POST'` |
| `$_GET['x'] ?? null` | Dato de la URL, o null si no viene | `$_GET['id']` |
| `isset($_POST['x'])` | Si una casilla está marcada | `isset($_POST['leido']) ? 1 : 0` |
| `trim()` | Quitar espacios de los extremos | `trim($_POST['titulo'])` |
| `empty()` | Vacío, 0, null o array sin nada | `if (empty($errores))` |
| `strlen()` | Longitud de un texto | `strlen($pass) < 6` |
| `in_array()` | Si un valor está en una lista | `in_array($g, $generos)` |
| `prepare` + `execute` | Consulta preparada con PDO | Ver sección 3 |
| `fetch()` / `fetchAll()` | Una fila o false / todas las filas | `$stmt->fetchAll()` |
| `rowCount()` | Filas afectadas o encontradas | `> 0` |
| `lastInsertId()` | Id del último INSERT | `$this->conn->lastInsertId()` |
| `json_encode()` | Array de PHP a JSON | `JSON_UNESCAPED_UNICODE \| JSON_PRETTY_PRINT` |
| `json_decode(file_get_contents("php://input"))` | Leer el JSON que llega en el cuerpo | `$data->titulo` |
| `http_response_code()` | Código de la respuesta | 200 bien, 201 creado, 400 datos mal, 401 sin permiso, 404 no existe, 405 método no permitido, 500 error del servidor |
| `parse_url(..., PHP_URL_PATH)` | Ruta de la URL sin `?...` | En api.php |
| `explode('/', $uri)` | Partir un texto en trozos | En api.php |
| `array_search()` | Posición de un valor en un array | `array_search('libros', $seg)` |
| `var_dump($x)` | Ver qué tiene una variable al depurar | Luego borrarlo |

## 10. Errores típicos y cómo arreglarlos

Casi todos los atascos de estos días han sido erratas pequeñas. Mira primero aquí.

| Lo que ves | Qué significa | Dónde mirar |
| --- | --- | --- |
| Parse error: unexpected ... en la línea N | Falta un `;`, una llave o un paréntesis | La línea de código anterior a la N |
| Página no encontrada | No hay `case` para esa acción | El switch del index.php y el `action=` del enlace |
| Página en blanco | Error escondido, o un header que no redirige | Pon `display_errors` en el index; revisa comillas del header |
| Class "X" not found | Falta el `require_once` o el nombre está mal | Los require del index.php |
| Failed to open stream: views/... | La vista no está en esa carpeta | La ruta del `require` y el explorador de VS Code |
| Call to a member function prepare() on null | No hay conexión | MySQL arrancado, nombre de la base de datos, `echo` en el catch |
| Undefined array key 0 | Corchete mal cerrado | `$_SERVER['REQUEST_METHOD'] === 'POST'` |
| Undefined variable $x en una vista | El controlador no creó esa variable antes del require | El método del controlador |
| Trying to access array offset on value of type bool | `fetch()` devolvió false, no existe ese registro | El id y la consulta del modelo |
| El if nunca entra | `=` en vez de `===` | La condición |
| Se guarda el dato equivocado | `$_POST['genero']` donde iba `autor` | Las líneas que leen `$_POST` |
| Cannot redeclare / unexpected public | Una función metida dentro de otra | Las llaves de cierre de cada método |
| No redirige y sale vacío | Comillas invertidas en el header | Usar comillas simples |
| Unknown column en el SQL | Nombre de columna mal escrito o con ñ sin comillas invertidas | El SQL del modelo y phpMyAdmin |
| Un dato sale bajo la columna equivocada | Falta el `<th>` de esa columna | La fila de cabeceras de la tabla |
| API devuelve 404 de Apache | El .htaccess no está activo | Usar `api/api.php/libros` |
| API dice JSON no válido | Body vacío o no está en JSON | En EchoAPI, Body en JSON y comillas dobles |

Después de cada cambio: guarda, recarga y prueba un caso que funcione y otro que tenga que fallar.
