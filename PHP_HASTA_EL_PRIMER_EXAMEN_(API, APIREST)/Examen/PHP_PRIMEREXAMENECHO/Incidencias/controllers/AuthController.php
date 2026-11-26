<?php
class AuthController
{
    private $usuario;

     /**
     * crea el modelo Usuario con la conexion
     */
    public function __construct($db)
    {
        $this->usuario = new Usuario($db);
    }

    /**
     * Enseña el formulario y si llega a post lo muestra
     */
    public function registro()
    {
        $errores = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->usuario->email = trim($_POST['email']);
            $this->usuario->nombre = trim($_POST['nombre']);
            $this->usuario->password = $_POST['password'];
            $confirmar = $_POST['confirmar'];

            // validaciones del enunciado
            if($this->usuario->buscarPorEmail()){
                $errores[] = 'Ese email ya está registrado';
            }
            if(strlen($this->usuario->password) < 6){
                $errores[] = 'La contraseña debe tener al menos 6 caracteres';
            }
            if ($this->usuario->password !== $confirmar){
                $errores[] = 'Las contraseñas no coinciden';
            }

            // Si no hay errores se guarda y se manda al login
            if(empty($errores)) {
                $this->usuario->registrar();
                header('Location: index.php?operacion=login');
                exit;
            }
        }

        // Se muestra la vista, con los errores si los hay
        require 'views/auth/registro.php';
    }

    /**
     * muestra el formulario si llega por post comprueba los datos
     */
    public function login()
    {
        $errores =[];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->usuario->email = trim($_POST['email']);
            $this->usuario->password = $_POST['password'];

            // El modelo comprueba la contraseña con password_verify
            if ($this->usuario->login()) {
                // Datos del usuario guardados en la sesión
                $_SESSION['usuario_id'] = $this->usuario->id;
                $_SESSION['usuario_nombre'] = $this->usuario->nombre;
                header('Location: index.php?operacion=listar');
                exit;
            }
            $errores[] = 'Email o contraseña incorrectos';
        }
        
        require 'views/auth/login.php';
    }
     /**
     * destruye la sesison y luego vuelve al login
     */
    public function logout()
    {
        //Vacia las variables de sesión
        $_SESSION = [];
        // Destruye la sesión
        session_destroy();

        header('Location: index.php?operacion=login');
        exit;
    }
}
?>