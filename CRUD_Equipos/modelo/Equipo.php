<?php
requiere_once '/Conexion.php';

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
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Definir la consulta con marcadores de posicion
            $sql_insert = "INSERT INTO {$tabla} (id, nombre, puntos, golesFavor, golesContra)
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
        // Establecer la conexion PDO
        $pdo = new PDO($dsn, $user, $password);

        // Configuracion para lanzar excepciones en caso de errores
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //2. Definir la consulta de actualización con marcadores de posición
        // El método UPDATE se realiza sobre la tabla, especificando las columnas a cambiar y una cláusula WHERE (por ID)
            $sql_update = "UPDATE {$tabla}
                           SET puntos"
    }

    public function eliminarDeBd(){

    }


    public function getEquipos(){

    }
}