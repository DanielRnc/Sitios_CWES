<?php
/**
 * CONTROLADOR: AuthController
 * 
 * Este controlador maneja todas las operaciones relacionadas con la autenticación:
 * - Login (inicio de sesión)
 * - Register (registro de nuevos usuarios)
 * - Logout (cierre de sesión)
 * 
 * Los controladores son el "pegamento" entre los modelos y las vistas en el patrón MVC
 */

class AuthController {
    
    /**
     * Mostrar el formulario de login
     */
    public function mostrarLogin() {
        // Si el usuario ya está logueado, redirigimos a sus tareas
        if (isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=misTareas");
            exit();
        }
        
        // Cargamos la vista de login
        require_once 'app/views/auth/login.php';
    }
    
    /**
     * Procesar el login del usuario
     */
    public function procesarLogin() {
        // Verificamos que se hayan enviado datos por POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Obtenemos los datos del formulario
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            // Validaciones básicas
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Por favor, rellena todos los campos";
                header("Location: index.php?action=login");
                exit();
            }
            
            // Conectamos con la base de datos y creamos un objeto Usuario
            $database = new Database();
            $db = $database->getConnection();
            $usuario = new Usuario($db);
            
            // Establecemos el email y verificamos si existe
            $usuario->email = $email;
            
            if ($usuario->emailExiste()) {
                // El email existe, ahora verificamos la contraseña
                if ($usuario->verificarPassword($password)) {
                    // Contraseña correcta, iniciamos sesión
                    $_SESSION['usuario_id'] = $usuario->id;
                    $_SESSION['usuario_nombre'] = $usuario->nombre;
                    $_SESSION['mensaje'] = "¡Bienvenido, " . $usuario->nombre . "!";
                    
                    header("Location: index.php?action=misTareas");
                    exit();
                } else {
                    // Contraseña incorrecta
                    $_SESSION['error'] = "Contraseña incorrecta";
                    header("Location: index.php?action=login");
                    exit();
                }
            } else {
                // El email no existe
                $_SESSION['error'] = "El usuario no existe";
                header("Location: index.php?action=login");
                exit();
            }
        }
    }
    
    /**
     * Mostrar el formulario de registro
     */
    public function mostrarRegister() {
        // Si el usuario ya está logueado, redirigimos a sus tareas
        if (isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=misTareas");
            exit();
        }
        
        // Cargamos la vista de registro
        require_once 'app/views/auth/register.php';
    }
    
    /**
     * Procesar el registro de un nuevo usuario
     */
    public function procesarRegister() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Obtenemos los datos del formulario
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            // Validaciones básicas
            if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
                $_SESSION['error'] = "Por favor, rellena todos los campos";
                header("Location: index.php?action=register");
                exit();
            }
            
            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Las contraseñas no coinciden";
                header("Location: index.php?action=register");
                exit();
            }
            
            if (strlen($password) < 6) {
                $_SESSION['error'] = "La contraseña debe tener al menos 6 caracteres";
                header("Location: index.php?action=register");
                exit();
            }
            
            // Conectamos con la base de datos
            $database = new Database();
            $db = $database->getConnection();
            $usuario = new Usuario($db);
            
            // Verificamos si el email ya existe
            $usuario->email = $email;
            if ($usuario->emailExiste()) {
                $_SESSION['error'] = "El email ya está registrado";
                header("Location: index.php?action=register");
                exit();
            }
            
            // Registramos al nuevo usuario
            $usuario->nombre = $nombre;
            $usuario->email = $email;
            $usuario->password = $password;
            
            if ($usuario->registrar()) {
                $_SESSION['mensaje'] = "Usuario registrado correctamente. Por favor, inicia sesión.";
                header("Location: index.php?action=login");
                exit();
            } else {
                $_SESSION['error'] = "Error al registrar el usuario";
                header("Location: index.php?action=register");
                exit();
            }
        }
    }
    
    /**
     * Cerrar sesión del usuario
     */
    public function logout() {
        // Destruimos todas las variables de sesión
        session_unset();
        session_destroy();
        
        // Redirigimos al inicio
        header("Location: index.php?action=home");
        exit();
    }
}
?>
