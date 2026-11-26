<?php
/**
 * Modelo Usuario
 * Gestiona las operaciones sobre la tabla usuarios
 */
class Usuario
{
    // Conexion a la base de datos
    private $conn;

    // Las propiedades 
    public $id;
    public $email;
    public $nombre;
    public $password;
    public $fecha_registro;

    /**
     * recibe la conexion PDO
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Comprueba si ya existe un usuario con ese email
     */
    public function buscarPorEmail()
    {
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Inserta el usuario con la contraseña cifrada 
     */
    public function registrar()
    {
        $stmt = $this->conn->prepare("INSERT INTO usuarios (email, nombre, password) VALUES(:email, :nombre, :password)");
        $hash = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':password', $hash);
        return $stmt->execute();
    }

    /**
     * Comprueba email y contraseña si esta todo bien claro
     */
    public function login()
    {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        $fila = $stmt->fetch();

        if ($fila && password_verify($this->password, $fila['password'])) {
            $this->id = $fila['id'];
            $this->nombre = $fila['nombre'];
            $this->fecha_registro = $fila['fecha_registro'];
            return true;
        }
        return false;
    }

    /**
     * devuelve todos los usuarios menos el indicado
     */
    public function listarOtros($id)
    {
        $stmt = $this->conn->prepare("SELECT id, nombre, email FROM usuarios WHERE id != :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * comprueba si existe un usuario con ese id
     */
    public function existeId($id)
    {
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>