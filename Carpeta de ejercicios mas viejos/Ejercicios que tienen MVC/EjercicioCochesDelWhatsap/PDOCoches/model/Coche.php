<?php
require_once("Conexion.php");
class Coche{
    //Variables o atributos
    public $marca;
    public $modelo;
    public $color;
    public $propietario;
    public $precio;
    public $id;
    public static $tabla="miscoches";

    public function __construct($miMarca, $miModelo, $miColor, $miPropietario, $miPrecio, $miId){
        $this->marca = $miMarca;
        $this->modelo = $miModelo;
        $this->color = $miColor;
        $this->propietario = $miPropietario;
        $this->precio=$miPrecio;
        $this->id=$miId;
    }

    //Funciones o métodos
    public function setMarca($miMarca){
        $this->marca = $miMarca;
    }

    public function getMarca(){
        return $this->marca;
    }

    public function setModelo($miModelo){
        $this->modelo = $miModelo;
    }

    public function getModelo(){
        return $this->modelo;
    }

    public function setColor($miColor){
        $this->color = $miColor;
    }

    public function getColor(){
        return $this->color;
    }

    public function setPropietario($miPropietario){
        $this->propietario = $miPropietario;
    }

    public function getPropietario(){
        return $this->propietario;
    }

    //obtener todos los coches de la base de datos
    public static function getAll(){
        $coches=[];
        $pdo=new Conexion();
        $t=self::$tabla;
        try{
            $db=$pdo->connect();
            //preparo el select y lo ejecuto
            $sql_query="SELECT * FROM {$t}";
            $resultado=$db->query($sql_query);
            //recorro el resultset que devuelve y lleno mi array con objetos de tipo coche
            while($coche=$resultado->fetch(PDO::FETCH_NUM)){
                $c=new Coche($coche[0],$coche[1],$coche[2],$coche[3],$coche[4],$coche[5]);
                array_push($coches, $c);
            }
        }catch (PDOException $e){
            //en caso de error informamos y matamos el proceso
            die("ha sucedido un error en la base de datos".$e->getMessage());
        }
        return $coches;
    }

    //obtener un coche por id
    public static function getCoche($id){
        $coche=null;
        $pdo=new Conexion();
        $t=self::$tabla;
        try{
            $db=$pdo->connect();
            //preparo el select y lo ejecuto
            $sql_query="SELECT * FROM {$t} WHERE id=$id";
            $resultado=$db->query($sql_query);
            //recorro el resultset que devuelve y maeo el coche
            while($c=$resultado->fetch(PDO::FETCH_NUM)){
                $coche=new Coche($c[0],$c[1],$c[2],$c[3],$c[4],$c[5]);
            }
        }catch (PDOException $e){
            //en caso de error informamos y matamos el proceso
            die("ha sucedido un error en la base de datos".$e->getMessage());
        }
        return $coche;
    }

    //borrar un coche por id
    public static function borrarCoche($id){
        $pdo=new Conexion();
        $t=self::$tabla;
        try{
            $db=$pdo->connect();
            //preparo el delete
            $sql_delete="DELETE FROM {$t} WHERE id=:id";
            $stmt=$db->prepare($sql_delete);
            //ejecuto el delete
            $stmt->execute([
                ':id'=>$id
            ]);
            //informamos en caso de delete exitoso
            if ($stmt->rowCount()>0) {
                echo "se elemino un coche de la base de datos";
            }
        }catch (PDOException $e){
            //en caso de error informamos y matamos el proceso
            die("ha sucedido un error en la base de datos".$e->getMessage());
        }
    }

    // Insertar un nuevo coche en la base de datos
    public function insertarCoche(){
        $pdo = new Conexion();
        $t = self::$tabla;
        try{
            $db = $pdo->connect();
            // Preparamos el insert usando los atributos del objeto actual
            $sql_insert = "INSERT INTO {$t} (marca, modelo, color, propietario, precio, id) VALUES (:marca, :modelo, :color, :propietario, :precio, :id)";
            $stmt = $db->prepare($sql_insert);
            $stmt->execute([
                ':marca' => $this->marca,
                ':modelo' => $this->modelo,
                ':color' => $this->color,
                ':propietario' => $this->propietario,
                ':precio' => $this->precio,
                ':id' => $this->id
            ]);
            
            if ($stmt->rowCount() > 0) {
                echo "Se insertó un nuevo coche correctamente.";
            }
        }catch(PDOException $e){
            die("Ha sucedido un error en la base de datos: " . $e->getMessage());
        }
    }
}