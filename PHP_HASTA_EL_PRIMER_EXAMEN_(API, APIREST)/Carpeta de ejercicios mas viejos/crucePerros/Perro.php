<?php
class Perro
{
    private $raza;
    private $color;
    private $peso;
    private $edad;
    private $sexo;

    public function __construct($raza, $color, $peso, $edad, $sexo)
    {
        $this->raza = $raza;
        $this->color = $color;
        $this->peso = $peso;
        $this->edad = $edad;
        $this->setSexo($sexo);
    }
        //GETTERS
    public function getRaza()
    {
        return $this->raza;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getPeso()
    {
        return $this->peso;
    }

    public function getEdad()
    {
        return $this->edad;
    }

    public function getSexo()
    {
        return $this->sexo;
    }
        //SETTERS
    public function setRaza($raza)
    {
        $this->raza = $raza;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function setPeso($peso)
    {
        $this->peso = $peso;
    }

    public function setEdad($edad)
    {
        $this->edad = $edad;
    }

    public function setSexo($sexo)
    {
        $sexoMinuscula = strtolower($sexo);
       if($sexoMinuscula=="macho" || $sexoMinuscula=="hembra"){
            $this->sexo = $sexoMinuscula;
        }else{
            $this->sexo= "error";
            echo "el sexo solo puede ser macho o hembra! <br>";
        }
                  
    }

    public function cruzar(Perro $otroPerro){
        $perroHijo = null;
        if($this->sexo !== $otroPerro->getSexo()){
            echo "cruce posible <br>";
            $perroHijo = new Perro($otroPerro->getRaza(),$otroPerro->getColor(),1,0,"hembra");
        }else{
            echo"cruce no posible <br> ";
        }

        return $perroHijo;
    }
        
    public function __toString() {
        return "Perro de raza: " . $this->raza . ", color " . $this->color . ", peso: " . $this->peso . ", edad: " .$this->edad . " y sexo: " . $this->sexo;
    }
}   