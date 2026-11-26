<?php
require_once("./model/Coche.php");
class CocheController{
    public $coches;
    function __construct(){
        $this->coches=Coche::getAll();
    }

    public function index(){
        //Asigno los coches a una variable que estará esperando la vista
        $rowset = $this->coches;
        //Le paso los datos a la vista
        require("view/index.php");
    }

    // public function ver($id){
    //     if (array_key_exists($id, $this->coches)) {
    //         //Si el elemento está en el array, lo muestro
    //         $row = $this->coches[$id];
    //         require("view/ver.php");
    //     } else {
    //         //Llamo al método por defecto del controlador
    //         $this->index();
    //     }
    // }

    public function verColor($color){
        //echo "has llegado al color, enhorabuena, ahora haz el resto que no se hace solo";
        $rowset=[];
        foreach ($this->coches as $coche){
            if($coche->color==$color){
                array_push($rowset, $coche);
            }
        }
        require("view/index.php");
    }

    public function baja($id){
        $victima=null;
        $victima=Coche::getCoche($id);
        if($victima!=null){
            $victima->borrarCoche($id);
            $this->coches=Coche::getAll();
            $this->index();
        }else{
            echo "no exite ese coche";
        }
    }

    public function alta(){
        // Comprobamos si se ha pulsado el botón de guardar del formulario
        if(isset($_POST['btnGuarda'])){
            // Recogemos los datos del formulario
            $marca = $_POST['marca'];
            $modelo = $_POST['modelo'];
            $color = $_POST['color'];
            $propietario = $_POST['propietario'];
            $precio = $_POST['precio'];
            $id = $_POST['id'];

            // Creamos una instancia de Coche con los datos recibidos
            $nuevoCoche = new Coche($marca, $modelo, $color, $propietario, $precio, $id);
            
            // Insertamos en la base de datos
            $nuevoCoche->insertarCoche();

            // Actualizamos el listado y volvemos al index
            $this->coches = Coche::getAll();
            $this->index();
        } else {
            // Si no se ha enviado el formulario, mostramos la vista con el formulario de inserción
            require("view/FormularioInsercion.php");
        }
    }
}