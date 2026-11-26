<?php
//Incluyo los archivos necesarios
require("./model/Coche.php");
require("./controller/CocheController.php");
//Instancio el controlador
$controller = new CocheController();
//$controller->index();
//Ruta de la home
$home="/www/PDOCoches/index.php/";
//Quito la home de la ruta de la barra de direcciones
$ruta=str_replace($home, "", $_SERVER["REQUEST_URI"]);
//Creo el array de ruta (filtrando los vacíos)
$array_ruta=array_filter(explode("/", $ruta));
//Decido la ruta en función de los elementos del array
if (isset($array_ruta[0])) {
    //ESTO probablemente sea mejor con un switch, pruebalo luego
    if($array_ruta[0] == "ver" && is_numeric($array_ruta[1])){
        //Llamo al método ver pasándole la clave que me están pidiendo
        $controller->ver($array_ruta[1]);
    }
    elseif($array_ruta[0]=="color" && !is_numeric($array_ruta[1])){
        $controller->verColor($array_ruta[1]);
    }
    elseif($array_ruta[0] == "baja" && is_numeric($array_ruta[1])){
        $controller->baja($array_ruta[1]);
    }
    elseif($array_ruta[0]=="alta"){
        $controller->alta();
    }
} else {
    //Llamo al método por defecto del controlador
    echo "se ha producido un error se mostraran todos los coches de la base de datos";
    $controller->index();
}