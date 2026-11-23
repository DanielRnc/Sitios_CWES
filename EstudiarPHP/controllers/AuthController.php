<?php
class AuthController
{
    private $usuario;

     /**
     * Constructor: crea el modelo Usuario con la conexión
     */
    public function __construct($db)
    {
        $this->usuario = new Usuario($db);
    }

    /**
     * Registro: muestra el formulario y, si llega por POST, lo procesa
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
            if($this->usuario->existeEmail()){
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
                header('Location: index.php?action=login');
                exit;
            }
        }

        // Se muestra la vista, con los errores si los hay
        require 'views/auth/registro.php';
    }
}
?>