<?php
requiere_once("Conexion.php");
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
    }
}