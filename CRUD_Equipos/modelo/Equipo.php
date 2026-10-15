<?php
require_once __DIR__ . '/Conexion.php';

class equipo
{
    public $id;
    public $nombre;
    public $puntos;
    public $golesFavor;
    public $golesContra;

    public function __construct($id,$nombre,$puntos,$golesFavor,$golesContra)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->puntos = $puntos;
        $this->golesFavor = $golesFavor;
        $this->golesContra = $golesContra;
    }

    //getters
    function getId(){
        return $this->id;
    }
    function getNombre(){
        return $this->nombre;
    }
    function getPuntos(){
        return $this->puntos;
    }
    function getGolesFavor(){
        return $this->golesFavor;
    }
    function getGolesContra(){
        return $this->golesContra;
    }
    //setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setPuntos($puntos)
    {
        $this->puntos = $puntos;
    }
    public function setGolesFavor($golesFavor)
    {
        $this->golesFavor = $golesFavor;
    }
    public function setGolesContra($golesContra)
    {
        $this->golesContra = $golesContra;
    }
    //CRUD
    public function insertarEnBd(){
        try {
            // Establecer la conexion PDO
            $objetoConexion = new Conexion();
            $pdo = $objetoConexion->connect();
            $pdo->setAttribute(PDO::ATR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Definir la consulta con marcadores de posicion
            $sql_insert = "INSERT INTO {$objetoConexion->tabla} (id, nombre, puntos, golesFavor, golesContra)
                           VALUES (:id, :nombre, :puntos, :gf, :gc)";
            
            // Preparar y ejecutar la sentencia
            $stmt = $pdo->prepare($sql_insert);
            $stmt->execute([
                ':nombre' => $this->nombre,
                ':puntos' => $this->puntos,
                ':gf'     => $this->golesFavor,
                ':gc'     => $this->golesContra,
            ]);

            // Verificar el resultado
            if ($stmt->rowCount() > 0) {
                echo "Funciona"
            }else{
                echo "No Funciona"
            }
        } catch (PDOException $e) {
           die("Error en la base de datos: " . $e->getMessage());
        }
    }

    public function modificarEnBd(){
        try{
            // Establecer la conexion PDO
            $objetoConexion = new Conexion();
            $pdo = $objetoConexion -> connect();

            // Configuracion para lanzar excepciones en caso de errores
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //2. Definir la consulta de actualización con marcadores de posición
            // El método UPDATE se realiza sobre la tabla, especificando las columnas a cambiar y una cláusula WHERE (por ID)
            $sql_update = "UPDATE {$objetoConexion->tabla}
                        SET puntos = :puntos, golesFavor = :gf, golesContra = :gc
                        WHERE id = :id";
                
            // Preparar la sentencia
            $stmt = $pdo->prepare($sql_update);

            // Asignar los valores y ejecutar la sentencia
            $stmt->execute([
                ':id' => $this->id,
                ':puntos' => $this->puntos,
                ':gf' => $this->golesFavor,
                'gc' => $this->golesContra
            ]);

            // Verificar y mostrar el resultado
            $filas_afectadas = $stmt->rowCount;

            if ($filas_afectadas > 0) {
            echo "funciona";
            } else {
                echo "no funciona";
            }
        } catch (PDOException $e) {
        // Manejo de errores
        die("Error en la base de datos durante la actualización: " . $e->getMessage()); 
    }
    
    }

    public function eliminarDeBd(){
        // Clave primaria para eliminar el registro
        $equipo_id_a_eliminar = $this->id; 

        try {
            // Establecer la conexión
            $objetoConexion = new Conexion();
            $pdo = $objetoConexion->connect();

            // Configuracion para lanzar excepciones en caso de errores
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Definir la consulta de eliminación con marcador de posición
            $sql_delete = "DELETE FROM {$objetoConexion->tabla} WHERE id = :id";

            // Preparar la sentencia
            $stmt = $pdo->prepare($sql_delete);

            // Asignar el valor al marcar de posición y ejecutar la sentencia
            $stmt->execute(['id' => $equipo_id_a_eliminar]);

            // Verificar y mostrar el resultado
            if($stmt->rowCount() > 0){
                echo "funciono";
            }else{
                echo "no funciono";
            }
        } catch (PDOException $e) {
            die("Error en la base de datos: " . $e->getMessage());
        }
    }


    public static function getEquipos(){
        // Establecer la conexión PDO
        $objetoConexion = new Conexion();
        $pdo = $objetoConexion->connect();

        // Configuracion para lanzar excepciones en caso de errores
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Definir la consulta para obtener todos los equipos
        $sql_obtener = "SELECT * FROM {$objetoConexion->tabla}";

        // Preparar la setencias
        $stmt = $pdo->prepare($sql_obtener);
}