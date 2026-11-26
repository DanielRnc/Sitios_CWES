<?php
/**
 * MODELO: Usuario
 * 
 * Esta clase representa el modelo de Usuario en la aplicación.
 * Contiene las propiedades y métodos para interactuar con la tabla 'usuarios' de la BD.
 * 
 * PROGRAMACIÓN ORIENTADA A OBJETOS (POO) en PHP:
 * - Una clase es una plantilla que define propiedades (atributos) y métodos (funciones)
 * - Un objeto es una instancia de una clase
 * - $this hace referencia al objeto actual
 * - public: accesible desde cualquier lugar
 * - private: solo accesible dentro de la clase
 */

class Usuario {
    // Propiedades de la clase (atributos del usuario)
    private $conn;
    private $table_name = "usuarios";
    
    public $id;
    public $nombre;
    public $email;
    public $password;
    
    /**
     * Constructor de la clase
     * Se ejecuta automáticamente al crear un nuevo objeto Usuario
     * Recibe la conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }
    
    /**
     * Registrar un nuevo usuario
     * @return bool true si se registra correctamente, false en caso contrario
     */
    public function registrar() {
        // Preparamos la consulta SQL
        $query = "INSERT INTO " . $this->table_name . " 
                  (nombre, email, password) 
                  VALUES (:nombre, :email, :password)";
        
        // Preparamos la sentencia
        $stmt = $this->conn->prepare($query);
        
        // Limpiamos los datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        // Encriptamos la contraseña
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
        
        // Vinculamos los parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $password_hash);
        
        // Ejecutamos la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Verificar si el email ya existe en la base de datos
     * @return bool true si existe, false si no existe
     */
    public function emailExiste() {
        $query = "SELECT id, nombre, email, password 
                  FROM " . $this->table_name . " 
                  WHERE email = :email 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        
        $num = $stmt->rowCount();
        
        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            return true;
        }
        return false;
    }
    
    /**
     * Verificar las credenciales del usuario (login)
     * @param string $password_introducida La contraseña introducida por el usuario
     * @return bool true si las credenciales son correctas, false en caso contrario
     */
    public function verificarPassword($password_introducida) {
        return password_verify($password_introducida, $this->password);
    }
    
    /**
     * Obtener información del usuario por ID
     * @return bool true si se encuentra el usuario, false en caso contrario
     */
    public function obtenerPorId() {
        $query = "SELECT id, nombre, email 
                  FROM " . $this->table_name . " 
                  WHERE id = :id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            return true;
        }
        return false;
    }
}
?>
