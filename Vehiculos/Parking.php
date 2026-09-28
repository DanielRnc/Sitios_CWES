<?php

class Vehiculo
{
    public $marca;
    public $modelo;
    public $color;
    public $matricula;
    public array $planta = ["superficie", "subterraneo1", "subterraneo2"];

    //Constructor
    public function __construct($marca, $modelo, $color, $matricula){
        $this->setMarca($marca);
        $this->setModelo($modelo);
        $this->setColor($color);
        $this->setMatricula($matricula);
    }

    // GETTER Y SETTER
    public function getMarca(){
        return $this->marca;
    }

    public function setMarca($marca) {
        $this->marca = $marca;
    }

    public function getModelo(){
        return $this->modelo;
    }

    public function setModelo($modelo){
         $this->modelo = $modelo;
    }

    public function getColor(){
        return $this->color;
    }

    public function setColor($color){
        $this->color = $color;
    }

    public function getMatricula(){
        return $this->matricula;
    }

    public function setMatricula($matricula){
        $this->matricula = $matricula;
    }

    public function getPlanta(){
        return $this->planta;
    }

    public function setPlanta($planta){
        $this->planta[] = $planta;
    }
}

class Autobus extends Vehiculo
{
    public $empresa;

    public function __construct($marca, $modelo, $color, $matricula, $empresa){
        parent::__construct($marca, $modelo, $color, $matricula);
        $this->empresa = $empresa;
    }
        
    
    public function puedeAparcar($planta){
        $condicion = false;

        if ($planta == "subterraneo1" || $planta == "subterraneo2")
            $condicion = false;
        elseif ($planta == "superficie")
            $condicion = true;
        
        return $condicion;
    }
}

class Furgoneta extends Vehiculo
{
    public function puedeAparcar(){
        
    }
}

class Coche extends Vehiculo
{
    public function puedeAparcar(){
        
    }
}
